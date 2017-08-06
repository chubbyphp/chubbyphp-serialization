<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization;

use Chubbyphp\Serialization\Mapping\ObjectMappingInterface;
use Chubbyphp\Serialization\Registry\ObjectMappingRegistryInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

final class Serializer implements SerializerInterface
{
    /**
     * @var ObjectMappingRegistryInterface
     */
    private $objectMappingRegistry;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param ObjectMappingRegistryInterface $objectMappingRegistry
     * @param LoggerInterface                $logger
     */
    public function __construct(ObjectMappingRegistryInterface $objectMappingRegistry, LoggerInterface $logger = null)
    {
        $this->objectMappingRegistry = $objectMappingRegistry;
        $this->logger = $logger ?? new NullLogger();
    }

    /**
     * @param Request $request
     * @param object  $object
     * @param string  $path
     *
     * @return array
     *
     * @throws NotObjectException
     */
    public function serialize(Request $request, $object, string $path = ''): array
    {
        if (!is_object($object)) {
            $this->logger->error('serialize: object without an object given {type}', ['type' => gettype($object)]);

            throw NotObjectException::createByType(gettype($object));
        }

        $objectClass = get_class($object);

        $objectMapping = $this->objectMappingRegistry->getObjectMappingForClass($objectClass);

        $fields = $this->serializeFields($request, $objectMapping, $object, $path);
        $embeddedFields = $this->serializeEmbeddedFields($request, $objectMapping, $object, $path);
        $links = $this->serializeLinks($request, $objectMapping, $object, $fields, $path);

        $data = $fields;

        $data['_type'] = $objectMapping->getType();

        if ([] !== $embeddedFields) {
            $data['_embedded'] = $embeddedFields;
        }

        if ([] !== $links) {
            $data['_links'] = $links;
        }

        return $data;
    }

    /**
     * @param Request                $request
     * @param ObjectMappingInterface $objectMapping
     * @param $object
     * @param string $path
     *
     * @return array
     */
    private function serializeFields(
        Request $request,
        ObjectMappingInterface $objectMapping,
        $object,
        string $path
    ): array {
        $data = [];
        foreach ($objectMapping->getFieldMappings() as $fieldMapping) {
            $name = $fieldMapping->getName();
            $subPath = '' !== $path ? $path.'.'.$name : $name;

            $this->logger->info('deserialize: path {path}', ['path' => $subPath]);

            $data[$fieldMapping->getName()] = $fieldMapping
                ->getFieldSerializer()
                ->serializeField($subPath, $request, $object, $this);
        }

        return $data;
    }

    /**
     * @param Request                $request
     * @param ObjectMappingInterface $objectMapping
     * @param $object
     * @param string $path
     *
     * @return array
     */
    private function serializeEmbeddedFields(
        Request $request,
        ObjectMappingInterface $objectMapping,
        $object,
        string $path
    ): array {
        $data = [];
        foreach ($objectMapping->getEmbeddedFieldMappings() as $fieldEmbeddedMapping) {
            $name = $fieldEmbeddedMapping->getName();
            $subPath = '' !== $path ? $path.'.'.$name : $name;

            $this->logger->info('deserialize: path {path}', ['path' => $subPath]);

            $data[$fieldEmbeddedMapping->getName()] = $fieldEmbeddedMapping
                ->getFieldSerializer()
                ->serializeField($subPath, $request, $object, $this);
        }

        return $data;
    }

    /**
     * @param Request                $request
     * @param ObjectMappingInterface $objectMapping
     * @param $object
     * @param array  $fields
     * @param string $path
     *
     * @return array
     */
    private function serializeLinks(
        Request $request,
        ObjectMappingInterface $objectMapping,
        $object,
        array $fields,
        string $path
    ): array {
        $data = [];
        foreach ($objectMapping->getLinkMappings() as $linkMapping) {
            $name = $linkMapping->getName();
            $subPath = '' !== $path ? $path.'._links.'.$name : '_links.'.$name;

            $this->logger->info('deserialize: path {path}', ['path' => $subPath]);

            $data[$linkMapping->getName()] = $linkMapping
                ->getLinkSerializer()
                ->serializeLink($subPath, $request, $object, $fields)
                ->jsonSerialize();
        }

        return $data;
    }
}
