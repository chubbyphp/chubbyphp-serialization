<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Mapping;

use Chubbyphp\Serialization\Accessor\PropertyAccessor;
use Chubbyphp\Serialization\Normalizer\CallbackFieldNormalizer;
use Chubbyphp\Serialization\Normalizer\DateTimeFieldNormalizer;
use Chubbyphp\Serialization\Normalizer\FieldNormalizer;
use Chubbyphp\Serialization\Normalizer\FieldNormalizerInterface;
use Chubbyphp\Serialization\Normalizer\Relation\EmbedManyFieldNormalizer;
use Chubbyphp\Serialization\Normalizer\Relation\EmbedOneFieldNormalizer;
use Chubbyphp\Serialization\Normalizer\Relation\ReferenceManyFieldNormalizer;
use Chubbyphp\Serialization\Normalizer\Relation\ReferenceOneFieldNormalizer;
use Chubbyphp\Serialization\Policy\NullPolicy;
use Chubbyphp\Serialization\Policy\PolicyInterface;

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
     * @var PolicyInterface|null
     */
    private $policy;

    /**
     * @param string $name
     */
    private function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * @param string $name
     * @param FieldNormalizerInterface|null $fieldNormalizer
     *
     * @return NormalizationFieldMappingBuilderInterface
     */
    public static function create(
        string $name,
        FieldNormalizerInterface $fieldNormalizer = null
    ): NormalizationFieldMappingBuilderInterface {
        $self = new self($name);
        $self->fieldNormalizer = $fieldNormalizer;

        return $self;
    }

    /**
     * @param string   $name
     * @param callable $callback
     *
     * @return NormalizationFieldMappingBuilderInterface
     */
    public static function createCallback(string $name, callable $callback): NormalizationFieldMappingBuilderInterface
    {
        $self = new self($name);
        $self->fieldNormalizer = new CallbackFieldNormalizer($callback);

        return $self;
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
     * @deprecated
     *
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
     * @deprecated
     *
     * @param FieldNormalizerInterface $fieldNormalizer
     *
     * @return NormalizationFieldMappingBuilderInterface
     */
    public function setFieldNormalizer(
        FieldNormalizerInterface $fieldNormalizer
    ): NormalizationFieldMappingBuilderInterface {
        @trigger_error(
            'Utilize second parameter of create method instead',
            E_USER_DEPRECATED
        );

        $this->fieldNormalizer = $fieldNormalizer;

        return $this;
    }

    /**
     * @param PolicyInterface $policy
     *
     * @return NormalizationFieldMappingBuilderInterface
     */
    public function setPolicy(PolicyInterface $policy): NormalizationFieldMappingBuilderInterface
    {
        $this->policy = $policy;

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
            $this->fieldNormalizer ?? new FieldNormalizer(new PropertyAccessor($this->name)),
            $this->policy ?? new NullPolicy()
        );
    }
}
