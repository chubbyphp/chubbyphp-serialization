<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Normalizer;

use Chubbyphp\Serialization\Mapping\NormalizationFieldMappingInterface;
use Chubbyphp\Serialization\Mapping\NormalizationObjectMappingInterface;
use Chubbyphp\Serialization\SerializerLogicException;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

final class Normalizer implements NormalizerInterface
{
    /**
     * @var NormalizerObjectMappingRegistryInterface
     */
    private $normalizerObjectMappingRegistry;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param NormalizerObjectMappingRegistryInterface $normalizerObjectMappingRegistry
     * @param LoggerInterface|null                     $logger
     */
    public function __construct(
        NormalizerObjectMappingRegistryInterface $normalizerObjectMappingRegistry,
        LoggerInterface $logger = null
    ) {
        $this->normalizerObjectMappingRegistry = $normalizerObjectMappingRegistry;
        $this->logger = $logger ?? new NullLogger();
    }

    /**
     * @param object                          $object
     * @param NormalizerContextInterface|null $context
     * @param string                          $path
     *
     * @return array
     */
    public function normalize($object, NormalizerContextInterface $context = null, string $path = ''): array
    {
        $this->validateDataType($object, $path);

        $context = $context ?? NormalizerContextBuilder::create()->getContext();

        $class = is_object($object) ? get_class($object) : $object;
        $objectMapping = $this->getObjectMapping($class);

        $fieldMappings = $objectMapping->getNormalizationFieldMappings($path);
        $fields = $this->getDataByFieldNormalizationMappings($context, $fieldMappings, $path, $object);

        $embeddedFieldMappings = $objectMapping->getNormalizationEmbeddedFieldMappings($path);
        $embeddedFields = $this->getDataByFieldNormalizationMappings($context, $embeddedFieldMappings, $path, $object);

        $data = $fields;

        if ([] !== $embeddedFields) {
            $data['_embedded'] = $embeddedFields;
        }

        $data['_type'] = $objectMapping->getNormalizationType();

        return $data;
    }

    /**
     * @param object $object
     * @param string $path
     *
     * @throws SerializerLogicException
     */
    private function validateDataType($object, string $path)
    {
        if (!is_object($object)) {
            $exception = SerializerLogicException::createWrongDataType(gettype($object), $path);

            $this->logger->error('serialize: {exception}', ['exception' => $exception->getMessage()]);

            throw $exception;
        }
    }

    /**
     * @param string $class
     *
     * @return NormalizationObjectMappingInterface
     *
     * @throws SerializerLogicException
     */
    private function getObjectMapping(string $class): NormalizationObjectMappingInterface
    {
        try {
            return $this->normalizerObjectMappingRegistry->getObjectMapping($class);
        } catch (SerializerLogicException $exception) {
            $this->logger->error('serialize: {exception}', ['exception' => $exception->getMessage()]);

            throw $exception;
        }
    }

    /**
     * @param NormalizerContextInterface           $context
     * @param NormalizationFieldMappingInterface[] $normalizationFieldMappings
     * @param string                               $path
     * @param $object
     *
     * @return array
     */
    private function getDataByFieldNormalizationMappings(
        NormalizerContextInterface $context,
        array $normalizationFieldMappings,
        string $path,
        $object
    ): array {
        $data = [];
        foreach ($normalizationFieldMappings as $normalizationFieldMapping) {
            $fieldNormalizer = $normalizationFieldMapping->getFieldNormalizer();

            if (!$this->isWithinGroup($context, $normalizationFieldMapping)) {
                continue;
            }

            $name = $normalizationFieldMapping->getName();

            $subPath = $this->getSubPathByName($path, $name);

            $this->logger->info('serialize: path {path}', ['path' => $subPath]);

            $data[$name] = $fieldNormalizer->normalizeField($subPath, $object, $context, $this);
        }

        return $data;
    }

    /**
     * @param NormalizerContextInterface         $context
     * @param NormalizationFieldMappingInterface $fieldMapping
     *
     * @return bool
     */
    private function isWithinGroup(
        NormalizerContextInterface $context,
        NormalizationFieldMappingInterface $fieldMapping
    ): bool {
        if ([] === $groups = $context->getGroups()) {
            return true;
        }

        foreach ($fieldMapping->getGroups() as $group) {
            if (in_array($group, $groups, true)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param string $path
     * @param string $name
     *
     * @return string
     */
    private function getSubPathByName(string $path, string $name): string
    {
        return '' === $path ? $name : $path.'.'.$name;
    }
}
