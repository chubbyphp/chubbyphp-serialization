<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Mapping;

use Chubbyphp\Serialization\Normalizer\FieldNormalizerInterface;

final class NormalizationFieldMapping implements NormalizationFieldMappingInterface
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

    /**
     * @param string                   $name
     * @param array                    $groups
     * @param FieldNormalizerInterface $fieldNormalizer
     */
    public function __construct($name, array $groups, FieldNormalizerInterface $fieldNormalizer)
    {
        $this->name = $name;
        $this->groups = $groups;
        $this->fieldNormalizer = $fieldNormalizer;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string[]
     */
    public function getGroups(): array
    {
        return $this->groups;
    }

    /**
     * @return FieldNormalizerInterface
     */
    public function getFieldNormalizer(): FieldNormalizerInterface
    {
        return $this->fieldNormalizer;
    }
}
