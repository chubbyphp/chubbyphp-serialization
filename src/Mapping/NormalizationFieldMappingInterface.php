<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Mapping;

use Chubbyphp\Serialization\Normalizer\FieldNormalizerInterface;
use Chubbyphp\Serialization\Policy\PolicyInterface;

/**
 * @method PolicyInterface getPolicy()
 */
interface NormalizationFieldMappingInterface
{
    public function getName(): string;

    /**
     * @deprecated
     *
     * @return string[]
     */
    public function getGroups(): array;

    public function getFieldNormalizer(): FieldNormalizerInterface;

    /*
     * @return PolicyInterface
     */
    //public function getPolicy(): PolicyInterface;
}
