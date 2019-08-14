<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Mapping;

use Chubbyphp\Serialization\Normalizer\FieldNormalizerInterface;
use Chubbyphp\Serialization\Policy\PolicyInterface;

/**
 * @method setPolicy(PolicyInterface $policy): self
 */
interface NormalizationFieldMappingBuilderInterface
{
    /**
     * @param string $name
     *
     * @return self
     */
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
     * @param array $groups
     *
     * @return self
     */
    public function setGroups(array $groups): self;

    /**
     * @deprecated
     *
     * @param FieldNormalizerInterface $fieldNormalizer
     *
     * @return self
     */
    public function setFieldNormalizer(FieldNormalizerInterface $fieldNormalizer): self;

    /**
     * @param PolicyInterface $policy
     *
     * @return self
     */
    //public function setPolicy(PolicyInterface $policy): self;

    /**
     * @return NormalizationFieldMappingInterface
     */
    public function getMapping(): NormalizationFieldMappingInterface;
}
