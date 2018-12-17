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
     * @return string
     */
    public function getNormalizationType(): string;

    /**
     * @param string $path
     *
     * @return NormalizationFieldMappingInterface[]
     */
    public function getNormalizationFieldMappings(string $path): array;
}
