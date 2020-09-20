<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Mapping;

use Chubbyphp\Serialization\Normalizer\FieldNormalizerInterface;
use Chubbyphp\Serialization\Policy\PolicyInterface;

interface NormalizationFieldMappingInterface
{
    public function getName(): string;

    public function getFieldNormalizer(): FieldNormalizerInterface;

    public function getPolicy(): PolicyInterface;
}
