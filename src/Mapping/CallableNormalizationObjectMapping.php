<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Mapping;

final class CallableNormalizationObjectMapping implements NormalizationObjectMappingInterface
{
    /**
     * @var callable
     */
    private $callable;

    private ?NormalizationObjectMappingInterface $mapping = null;

    public function __construct(private string $class, callable $callable)
    {
        $this->callable = $callable;
    }

    public function getClass(): string
    {
        return $this->class;
    }

    public function getNormalizationType(): ?string
    {
        return $this->getMapping()->getNormalizationType();
    }

    /**
     * @return array<int, NormalizationFieldMappingInterface>
     */
    public function getNormalizationFieldMappings(string $path): array
    {
        return $this->getMapping()->getNormalizationFieldMappings($path);
    }

    /**
     * @return array<int, NormalizationFieldMappingInterface>
     */
    public function getNormalizationEmbeddedFieldMappings(string $path): array
    {
        return $this->getMapping()->getNormalizationEmbeddedFieldMappings($path);
    }

    /**
     * @return array<int, NormalizationLinkMappingInterface>
     */
    public function getNormalizationLinkMappings(string $path): array
    {
        return $this->getMapping()->getNormalizationLinkMappings($path);
    }

    private function getMapping(): NormalizationObjectMappingInterface
    {
        if (null === $this->mapping) {
            $callable = $this->callable;
            $this->mapping = $callable();
        }

        return $this->mapping;
    }
}
