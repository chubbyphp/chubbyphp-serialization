<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Registry;

use Chubbyphp\Serialization\Mapping\ObjectMappingInterface;

final class ObjectMappingRegistry implements ObjectMappingRegistryInterface
{
    /**
     * @var ObjectMappingInterface[]
     */
    private $mappings;

    /**
     * @param ObjectMappingInterface[] $mappings
     */
    public function __construct(array $mappings)
    {
        $this->mappings = [];
        foreach ($mappings as $mapping) {
            $this->add($mapping);
        }
    }

    /**
     * @param ObjectMappingInterface $mapping
     */
    private function add(ObjectMappingInterface $mapping)
    {
        $this->mappings[$mapping->getClass()] = $mapping;
    }

    /**
     * @param string $class
     *
     * @return ObjectMappingInterface
     *
     * @throws \InvalidArgumentException
     */
    public function getObjectMappingForClass(string $class): ObjectMappingInterface
    {
        if (isset($this->mappings[$class])) {
            return $this->mappings[$class];
        }

        throw new \InvalidArgumentException(sprintf('There is no mapping for class: %s', $class));
    }
}
