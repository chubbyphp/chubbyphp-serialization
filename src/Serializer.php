<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization;

use Chubbyphp\Serialization\Registry\ObjectMappingRegistryInterface;
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
     * @param LoggerInterface $logger
     */
    public function __construct(ObjectMappingRegistryInterface $objectMappingRegistry, LoggerInterface $logger = null)
    {
        $this->objectMappingRegistry = $objectMappingRegistry;
        $this->logger = $logger ?? new NullLogger();
    }

    /**
     * @param object $object
     * @param string $path
     * @return array
     * @throws NotObjectException
     */
    public function serialize($object, string $path = ''): array
    {
        if (!is_object($object)) {
            $this->logger->error('serialize: object without an object given {type}', ['type' => gettype($object)]);

            throw NotObjectException::createByType(gettype($object));
        }

        $objectClass = get_class($object);

        $objectMapping = $this->objectMappingRegistry->getObjectMappingForClass($objectClass);

        $data = [];
        foreach ($objectMapping->getFieldMappings() as $fieldMapping) {
            $name = $fieldMapping->getName();
            $subPath = '' !== $path ? $path . '.' . $name : $name;

            $this->logger->info('deserialize: path {path}', ['path' => $subPath]);

            $data[$fieldMapping->getName()] = $fieldMapping->getFieldSerializer()->serializeField($subPath, $object, $this);
        }

        foreach ($objectMapping->getEmbeddedFieldMappings() as $fieldMapping) {
            $name = $fieldMapping->getName();
            $subPath = '' !== $path ? $path . '._embedded.' . $name : '_embedded.' . $name;

            $this->logger->info('deserialize: path {path}', ['path' => $subPath]);

            if (!isset($data['_embedded'])) {
                $data['_embedded'] = [];
            }

            $data['_embedded'][$fieldMapping->getName()] = $fieldMapping->getFieldSerializer()->serializeField($subPath, $object, $this);
        }

        return $data;
    }
}
