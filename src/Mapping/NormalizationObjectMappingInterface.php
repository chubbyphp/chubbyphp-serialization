<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Mapping;

interface NormalizationObjectMappingInterface
{
    /**
     * @return string
     */
    public function getClass(): string;

    /**
     * @return string|null
     */
    public function getNormalizationType();

    /**
     * @param string $path
     *
     * @return NormalizationFieldMappingInterface[]
     */
    public function getNormalizationFieldMappings(string $path): array;

    /**
     * @param string $path
     *
     * @return NormalizationFieldMappingInterface[]
     */
    public function getNormalizationEmbeddedFieldMappings(string $path): array;

    /**
     * @param string $path
     *
     * @return NormalizationLinkMappingInterface[]
     */
    public function getNormalizationLinkMappings(string $path): array;
}
