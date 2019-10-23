<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Normalizer;

use Chubbyphp\Serialization\Mapping\NormalizationObjectMappingInterface;
use Chubbyphp\Serialization\SerializerLogicException;

final class NormalizerObjectMappingRegistry implements NormalizerObjectMappingRegistryInterface
{
    /**
     * @var NormalizationObjectMappingInterface[]
     */
    private $objectMappings;

    /**
     * @param array $objectMappings
     */
    public function __construct(array $objectMappings)
    {
        $this->objectMappings = [];
        foreach ($objectMappings as $objectMapping) {
            $this->addObjectMapping($objectMapping);
        }
    }

    /**
     * @param string $class
     *
     * @throws SerializerLogicException
     *
     * @return NormalizationObjectMappingInterface
     */
    public function getObjectMapping(string $class): NormalizationObjectMappingInterface
    {
        $reflectionClass = new \ReflectionClass($class);

        if (in_array('Doctrine\Common\Persistence\Proxy', $reflectionClass->getInterfaceNames(), true)) {
            /** @var \ReflectionClass $reflectionParentClass */
            $reflectionParentClass = $reflectionClass->getParentClass();
            $class = $reflectionParentClass->getName();
        }

        if (isset($this->objectMappings[$class])) {
            return $this->objectMappings[$class];
        }

        throw SerializerLogicException::createMissingMapping($class);
    }

    /**
     * @param NormalizationObjectMappingInterface $objectMapping
     */
    private function addObjectMapping(NormalizationObjectMappingInterface $objectMapping): void
    {
        $this->objectMappings[$objectMapping->getClass()] = $objectMapping;
    }
}
