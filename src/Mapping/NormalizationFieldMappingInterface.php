<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Mapping;

use Chubbyphp\Serialization\Normalizer\FieldNormalizerInterface;

interface NormalizationFieldMappingInterface
{
    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return string[]
     */
    public function getGroups(): array;

    /**
     * @return FieldNormalizerInterface
     */
    public function getFieldNormalizer(): FieldNormalizerInterface;

    /*
     * @return object|string|null
     */
    //public function getPermission();
}
