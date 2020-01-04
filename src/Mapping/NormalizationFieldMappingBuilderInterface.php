<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Mapping;

use Chubbyphp\Serialization\Normalizer\FieldNormalizerInterface;
use Chubbyphp\Serialization\Policy\PolicyInterface;

/**
 * @method NormalizationFieldMappingBuilderInterface setPolicy(PolicyInterface $policy)
 */
interface NormalizationFieldMappingBuilderInterface
{
    public static function create(string $name): self;

    /**
     * @param string                        $name
     * @param FieldNormalizerInterface|null $fieldNormalizer
     *
     * @return NormalizationFieldMappingBuilderInterface
     */
    //public static function create(string $name, FieldNormalizerInterface $fieldNormalizer = null): self;

    /**
     * @deprecated
     *
     * @param array<int, string> $groups
     */
    public function setGroups(array $groups): self;

    /**
     * @deprecated
     */
    public function setFieldNormalizer(FieldNormalizerInterface $fieldNormalizer): self;

    /**
     * @param PolicyInterface $policy
     *
     * @return self
     */
    //public function setPolicy(PolicyInterface $policy): self;

    public function getMapping(): NormalizationFieldMappingInterface;
}
