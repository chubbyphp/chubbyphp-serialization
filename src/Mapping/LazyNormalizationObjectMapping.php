<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Mapping;

use Psr\Container\ContainerInterface;

final class LazyNormalizationObjectMapping implements NormalizationObjectMappingInterface
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
     * @param string $serviceId
     */
    public function __construct(ContainerInterface $container, $serviceId, string $class)
    {
        $this->container = $container;
        $this->serviceId = $serviceId;
        $this->class = $class;
    }

    public function getClass(): string
    {
        return $this->class;
    }

    /**
     * @return string|null
     */
    public function getNormalizationType()
    {
        return $this->container->get($this->serviceId)->getNormalizationType();
    }

    /**
     * @return NormalizationFieldMappingInterface[]
     */
    public function getNormalizationFieldMappings(string $path): array
    {
        return $this->container->get($this->serviceId)->getNormalizationFieldMappings($path);
    }

    /**
     * @return NormalizationFieldMappingInterface[]
     */
    public function getNormalizationEmbeddedFieldMappings(string $path): array
    {
        return $this->container->get($this->serviceId)->getNormalizationEmbeddedFieldMappings($path);
    }

    /**
     * @return NormalizationLinkMappingInterface[]
     */
    public function getNormalizationLinkMappings(string $path): array
    {
        return $this->container->get($this->serviceId)->getNormalizationLinkMappings($path);
    }
}
