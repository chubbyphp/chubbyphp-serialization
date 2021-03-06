<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Mapping;

interface NormalizationObjectMappingInterface
{
    public function getClass(): string;

    public function getNormalizationType(): ?string;

    /**
     * @return array<int, NormalizationFieldMappingInterface>
     */
    public function getNormalizationFieldMappings(string $path): array;

    /**
     * @return array<int, NormalizationFieldMappingInterface>
     */
    public function getNormalizationEmbeddedFieldMappings(string $path): array;

    /**
     * @return array<int, NormalizationLinkMappingInterface>
     */
    public function getNormalizationLinkMappings(string $path): array;
}
