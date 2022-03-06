<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Mapping;

use Psr\Container\ContainerInterface;

final class LazyNormalizationObjectMapping implements NormalizationObjectMappingInterface
{
    public function __construct(private ContainerInterface $container, private string $serviceId, private string $class)
    {
    }

    public function getClass(): string
    {
        return $this->class;
    }

    public function getNormalizationType(): ?string
    {
        return $this->container->get($this->serviceId)->getNormalizationType();
    }

    /**
     * @return array<int, NormalizationFieldMappingInterface>
     */
    public function getNormalizationFieldMappings(string $path): array
    {
        return $this->container->get($this->serviceId)->getNormalizationFieldMappings($path);
    }

    /**
     * @return array<int, NormalizationFieldMappingInterface>
     */
    public function getNormalizationEmbeddedFieldMappings(string $path): array
    {
        return $this->container->get($this->serviceId)->getNormalizationEmbeddedFieldMappings($path);
    }

    /**
     * @return array<int, NormalizationLinkMappingInterface>
     */
    public function getNormalizationLinkMappings(string $path): array
    {
        return $this->container->get($this->serviceId)->getNormalizationLinkMappings($path);
    }
}
