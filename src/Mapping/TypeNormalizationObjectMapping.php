<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Mapping;

interface TypeNormalizationObjectMapping
{
    /**
     * @return string
     */
    public function getNormalizationType(): string;
}
