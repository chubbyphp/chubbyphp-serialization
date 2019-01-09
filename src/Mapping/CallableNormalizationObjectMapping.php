<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Mapping;

final class CallableNormalizationObjectMapping implements NormalizationObjectMappingInterface
{
    /**
     * @var string
     */
    private $class;

    /**
     * @var callable
     */
    private $callable;

    /**
     * @var NormalizationObjectMappingInterface|null
     */
    private $mapping;

    /**
     * @param string   $class
     * @param callable $callable
     */
    public function __construct(string $class, callable $callable)
    {
        $this->class = $class;
        $this->callable = $callable;
    }

    /**
     * @return string
     */
    public function getClass(): string
    {
        return $this->class;
    }

    /**
     * @return string|null
     */
    public function getNormalizationType()
    {
        return $this->getMapping()->getNormalizationType();
    }

    /**
     * @param string $path
     *
     * @return NormalizationFieldMappingInterface[]
     */
    public function getNormalizationFieldMappings(string $path): array
    {
        return $this->getMapping()->getNormalizationFieldMappings($path);
    }

    /**
     * @param string $path
     *
     * @return NormalizationFieldMappingInterface[]
     */
    public function getNormalizationEmbeddedFieldMappings(string $path): array
    {
        return $this->getMapping()->getNormalizationEmbeddedFieldMappings($path);
    }

    /**
     * @param string $path
     *
     * @return NormalizationLinkMappingInterface[]
     */
    public function getNormalizationLinkMappings(string $path): array
    {
        return $this->getMapping()->getNormalizationLinkMappings($path);
    }

    /**
     * @return NormalizationObjectMappingInterface
     */
    private function getMapping(): NormalizationObjectMappingInterface
    {
        if (null === $this->mapping) {
            $callable = $this->callable;
            $this->mapping = $callable();
        }

        return $this->mapping;
    }
}
