<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Mapping;

use Chubbyphp\Serialization\Accessor\PropertyAccessor;
use Chubbyphp\Serialization\Normalizer\DateTimeFieldNormalizer;
use Chubbyphp\Serialization\Normalizer\FieldNormalizer;
use Chubbyphp\Serialization\Normalizer\FieldNormalizerInterface;
use Chubbyphp\Serialization\Normalizer\Relation\EmbedManyFieldNormalizer;
use Chubbyphp\Serialization\Normalizer\Relation\EmbedOneFieldNormalizer;
use Chubbyphp\Serialization\Normalizer\Relation\ReferenceManyFieldNormalizer;
use Chubbyphp\Serialization\Normalizer\Relation\ReferenceOneFieldNormalizer;

final class NormalizationFieldMappingBuilder implements NormalizationFieldMappingBuilderInterface
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var array
     */
    private $groups = [];

    /**
     * @var FieldNormalizerInterface|null
     */
    private $fieldNormalizer;

    /**
     * @param string $name
     */
    private function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * @param string $name
     *
     * @return NormalizationFieldMappingBuilderInterface
     */
    public static function create(string $name): NormalizationFieldMappingBuilderInterface
    {
        return new self($name);
    }

    /**
     * @param string $name
     * @param string $format
     *
     * @return NormalizationFieldMappingBuilderInterface
     */
    public static function createDateTime(string $name, string $format = 'c'): NormalizationFieldMappingBuilderInterface
    {
        $self = new self($name);
        $self->fieldNormalizer = new DateTimeFieldNormalizer(new PropertyAccessor($name), $format);

        return $self;
    }

    /**
     * @param string $name
     *
     * @return NormalizationFieldMappingBuilderInterface
     */
    public static function createEmbedMany(string $name): NormalizationFieldMappingBuilderInterface
    {
        $self = new self($name);
        $self->fieldNormalizer = new EmbedManyFieldNormalizer(new PropertyAccessor($name));

        return $self;
    }

    /**
     * @param string $name
     *
     * @return NormalizationFieldMappingBuilderInterface
     */
    public static function createEmbedOne(string $name): NormalizationFieldMappingBuilderInterface
    {
        $self = new self($name);
        $self->fieldNormalizer = new EmbedOneFieldNormalizer(new PropertyAccessor($name));

        return $self;
    }

    /**
     * @param string $name
     * @param string $idName
     *
     * @return NormalizationFieldMappingBuilderInterface
     */
    public static function createReferenceMany(
        string $name,
        string $idName = 'id'
    ): NormalizationFieldMappingBuilderInterface {
        $self = new self($name);
        $self->fieldNormalizer = new ReferenceManyFieldNormalizer(
            new PropertyAccessor($idName),
            new PropertyAccessor($name)
        );

        return $self;
    }

    /**
     * @param string $name
     * @param string $idName
     *
     * @return NormalizationFieldMappingBuilderInterface
     */
    public static function createReferenceOne(
        string $name,
        string $idName = 'id'
    ): NormalizationFieldMappingBuilderInterface {
        $self = new self($name);
        $self->fieldNormalizer = new ReferenceOneFieldNormalizer(
            new PropertyAccessor($idName),
            new PropertyAccessor($name)
        );

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
        return new NormalizationFieldMapping(
            $this->name,
            $this->groups,
            $this->fieldNormalizer ?? new FieldNormalizer(new PropertyAccessor($this->name))
        );
    }
}
