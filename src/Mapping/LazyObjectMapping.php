<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Mapping;

use Chubbyphp\Serialization\Serializer\Field\FieldSerializerInterface;
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
     * @return FieldSerializerInterface[]
     */
    public function getFieldSerializers(): array
    {
        return $this->container->get($this->serviceId)->getFieldSerializers();
    }
}
