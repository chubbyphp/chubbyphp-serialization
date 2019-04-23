<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Mapping;

use Chubbyphp\Serialization\Normalizer\FieldNormalizerInterface;

interface NormalizationFieldMappingBuilderInterface
{
    /**
     * @param string $name
     *
     * @return self
     */
    public static function create(string $name): self;

    /**
     * @param array $groups
     *
     * @return self
     */
    public function setGroups(array $groups): self;

    /**
     * @param FieldNormalizerInterface $fieldNormalizer
     *
     * @return self
     */
    public function setFieldNormalizer(FieldNormalizerInterface $fieldNormalizer): self;

    /**
     * @param object|string|null $permission
     *
     * @return self
     */
    //public function setPermissions($permission): self;

    /**
     * @return NormalizationFieldMappingInterface
     */
    public function getMapping(): NormalizationFieldMappingInterface;
}
