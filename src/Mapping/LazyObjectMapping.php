<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Mapping;

use Interop\Container\ContainerInterface;

final class LazyObjectMapping implements ObjectMappingInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var string
     */
    private $serviceId;

    /**
     * @var string
     */
    private $class;

    /**
     * @param ContainerInterface $container
     * @param string             $serviceId
     * @param string             $class
     */
    public function __construct(ContainerInterface $container, string $serviceId, string $class)
    {
        $this->container = $container;
        $this->serviceId = $serviceId;
        $this->class = $class;
    }

    /**
     * @return string
     */
    public function getClass(): string
    {
        return $this->class;
    }

    /**
     * @return FieldMappingInterface[]
     */
    public function getFieldMappings(): array
    {
        return $this->container->get($this->serviceId)->getFieldMappings();
    }

    /**
     * @return FieldMappingInterface[]
     */
    public function getEmbeddedFieldMappings(): array
    {
        return $this->container->get($this->serviceId)->getEmbeddedFieldMappings();
    }

    /**
     * @return LinkMappingInterface[]
     */
    public function getLinkMappings(): array
    {
        return $this->container->get($this->serviceId)->getLinkMappings();
    }
}
