<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Normalizer;

use Chubbyphp\Serialization\Mapping\NormalizationFieldMappingInterface;
use Chubbyphp\Serialization\Mapping\NormalizationLinkMappingInterface;
use Chubbyphp\Serialization\Mapping\NormalizationObjectMappingInterface;
use Chubbyphp\Serialization\Policy\GroupPolicy;
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

    public function __construct(
        NormalizerObjectMappingRegistryInterface $normalizerObjectMappingRegistry,
        ?LoggerInterface $logger = null
    ) {
        $this->normalizerObjectMappingRegistry = $normalizerObjectMappingRegistry;
        $this->logger = $logger ?? new NullLogger();
    }

    /**
     * @param object $object
     *
     * @throws SerializerLogicException
     *
     * @return array<mixed>
     */
    public function normalize(
        $object,
        ?NormalizerContextInterface $context = null,
        string $path = ''
    ): array {
        $context = $context ?? NormalizerContextBuilder::create()->getContext();

        $class = get_class($object);
        $objectMapping = $this->getObjectMapping($class);

        $fieldMappings = $objectMapping->getNormalizationFieldMappings($path);

        $data = $this->getFieldsByFieldNormalizationMappings($context, $fieldMappings, $path, $object);

        $embeddedMappings = $objectMapping->getNormalizationEmbeddedFieldMappings($path);
        $embedded = $this->getFieldsByFieldNormalizationMappings($context, $embeddedMappings, $path, $object);

        $linkMappings = $objectMapping->getNormalizationLinkMappings($path);
        $links = $this->getLinksByLinkNormalizationMappings($context, $linkMappings, $path, $object);

        if ([] !== $embedded) {
            $data['_embedded'] = $embedded;
        }

        if ([] !== $links) {
            $data['_links'] = $links;
        }

        if (null !== $type = $objectMapping->getNormalizationType()) {
            $data['_type'] = $type;
        }

        return $data;
    }

    /**
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
     * @param array<int, NormalizationFieldMappingInterface> $normalizationFieldMappings
     *
     * @return array<mixed>
     */
    private function getFieldsByFieldNormalizationMappings(
        NormalizerContextInterface $context,
        array $normalizationFieldMappings,
        string $path,
        object $object
    ): array {
        $data = [];
        foreach ($normalizationFieldMappings as $normalizationFieldMapping) {
            $name = $normalizationFieldMapping->getName();

            $subPath = $this->getSubPathByName($path, $name);

            if (!$this->isCompliant($subPath, $object, $context, $normalizationFieldMapping)) {
                continue;
            }

            if (!$this->isWithinGroup($context, $normalizationFieldMapping)) {
                continue;
            }

            $fieldNormalizer = $normalizationFieldMapping->getFieldNormalizer();

            $this->logger->info('serialize: path {path}', ['path' => $subPath]);

            $data[$name] = $fieldNormalizer->normalizeField($subPath, $object, $context, $this);
        }

        return $data;
    }

    /**
     * @param array<int, NormalizationLinkMappingInterface> $normalizationLinkMappings
     *
     * @return array<mixed>
     */
    private function getLinksByLinkNormalizationMappings(
        NormalizerContextInterface $context,
        array $normalizationLinkMappings,
        string $path,
        object $object
    ): array {
        $links = [];
        foreach ($normalizationLinkMappings as $normalizationLinkMapping) {
            if (!$this->isCompliant($path, $object, $context, $normalizationLinkMapping)) {
                continue;
            }

            if (!$this->isWithinGroup($context, $normalizationLinkMapping)) {
                continue;
            }

            $linkNormalizer = $normalizationLinkMapping->getLinkNormalizer();

            if (null === $link = $linkNormalizer->normalizeLink($path, $object, $context)) {
                continue;
            }

            $links[$normalizationLinkMapping->getName()] = $link;
        }

        return $links;
    }

    /**
     * @param NormalizationFieldMappingInterface|NormalizationLinkMappingInterface $mapping
     */
    private function isCompliant(string $path, object $object, NormalizerContextInterface $context, $mapping): bool
    {
        if (!is_callable([$mapping, 'getPolicy'])) {
            return true;
        }

        if (is_callable([$mapping->getPolicy(), 'isCompliantIncludingPath'])) {
            return $mapping->getPolicy()->isCompliantIncludingPath($path, $object, $context);
        }

        return $mapping->getPolicy()->isCompliant($context, $object);
    }

    /**
     * @param NormalizationFieldMappingInterface|NormalizationLinkMappingInterface $mapping
     */
    private function isWithinGroup(NormalizerContextInterface $context, $mapping): bool
    {
        if ([] === $groups = $context->getGroups()) {
            return true;
        }

        @trigger_error(
            sprintf(
                'Use "%s" instead of "%s::setGroups"',
                GroupPolicy::class,
                NormalizerContextInterface::class
            ),
            E_USER_DEPRECATED
        );

        foreach ($mapping->getGroups() as $group) {
            if (in_array($group, $groups, true)) {
                return true;
            }
        }

        return false;
    }

    private function getSubPathByName(string $path, string $name): string
    {
        return '' === $path ? $name : $path.'.'.$name;
    }
}
