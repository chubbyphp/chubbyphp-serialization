<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Mapping;

interface LinkNormalizationObjectMapping
{
    /**
     * @param string $path
     *
     * @return NormalizationLinkMappingInterface[]
     */
    public function getNormalizationLinkMappings(string $path): array;
}
