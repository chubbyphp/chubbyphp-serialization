<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Mapping;

interface EmbeddedNormalizationObjectMapping
{
    /**
     * @param string $path
     *
     * @return NormalizationFieldMappingInterface[]
     */
    public function getNormalizationEmbeddedFieldMappings(string $path): array;
}
