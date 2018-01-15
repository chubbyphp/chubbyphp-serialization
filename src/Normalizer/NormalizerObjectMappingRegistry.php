<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Normalizer;

use Chubbyphp\Serialization\SerializerLogicException;
use Chubbyphp\Serialization\Mapping\NormalizationObjectMappingInterface;

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
     * @param NormalizationObjectMappingInterface $objectMapping
     */
    private function addObjectMapping(NormalizationObjectMappingInterface $objectMapping)
    {
        $this->objectMappings[$objectMapping->getClass()] = $objectMapping;
    }

    /**
     * @param string $class
     *
     * @return NormalizationObjectMappingInterface
     *
     * @throws SerializerLogicException
     */
    public function getObjectMapping(string $class): NormalizationObjectMappingInterface
    {
        $ref = new \ReflectionClass($class);

        if (true === in_array('Doctrine\Common\Persistence\Proxy', $ref->getInterfaceNames(), true)) {
            $class = $ref->getParentClass()->name;
        }

        if (isset($this->objectMappings[$class])) {
            return $this->objectMappings[$class];
        }

        throw SerializerLogicException::createMissingMapping($class);
    }
}
