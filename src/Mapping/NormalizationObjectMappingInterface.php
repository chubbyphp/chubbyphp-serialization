<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Mapping;

interface NormalizationObjectMappingInterface
{
    public function getClass(): string;

    /**
     * @return string|null
     */
    public function getNormalizationType();

    /**
     * @return NormalizationFieldMappingInterface[]
     */
    public function getNormalizationFieldMappings(string $path): array;

    /**
     * @return NormalizationFieldMappingInterface[]
     */
    public function getNormalizationEmbeddedFieldMappings(string $path): array;

    /**
     * @return NormalizationLinkMappingInterface[]
     */
    public function getNormalizationLinkMappings(string $path): array;
}
