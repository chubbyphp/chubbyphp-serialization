<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Mapping;

use Chubbyphp\Serialization\Accessor\PropertyAccessor;
use Chubbyphp\Serialization\Normalizer\FieldNormalizer;
use Chubbyphp\Serialization\Normalizer\FieldNormalizerInterface;

final class NormalizationFieldMappingBuilder implements NormalizationFieldMappingBuilderInterface
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var array
     */
    private $groups;

    /**
     * @var FieldNormalizerInterface
     */
    private $fieldNormalizer;

    private function __construct()
    {
    }

    /**
     * @param string $name
     *
     * @return NormalizationFieldMappingBuilderInterface
     */
    public static function create(string $name): NormalizationFieldMappingBuilderInterface
    {
        $self = new self();
        $self->name = $name;
        $self->groups = [];
        $self->fieldNormalizer = new FieldNormalizer(new PropertyAccessor($name));

        return $self;
    }

    /**
     * @param array $groups
     *
     * @return NormalizationFieldMappingBuilderInterface
     */
    public function setGroups(array $groups): NormalizationFieldMappingBuilderInterface
    {
        $this->groups = $groups;

        return $this;
    }

    /**
     * @param FieldNormalizerInterface $fieldNormalizer
     *
     * @return NormalizationFieldMappingBuilderInterface
     */
    public function setFieldNormalizer(
        FieldNormalizerInterface $fieldNormalizer
    ): NormalizationFieldMappingBuilderInterface {
        $this->fieldNormalizer = $fieldNormalizer;

        return $this;
    }

    /**
     * @return NormalizationFieldMappingInterface
     */
    public function getMapping(): NormalizationFieldMappingInterface
    {
        return new NormalizationFieldMapping($this->name, $this->groups, $this->fieldNormalizer);
    }
}
