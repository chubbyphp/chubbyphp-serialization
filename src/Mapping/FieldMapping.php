<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Mapping;

use Chubbyphp\Serialization\Serializer\Field\FieldSerializerInterface;
use Chubbyphp\Serialization\Serializer\Field\PropertySerializer;

final class FieldMapping implements FieldMappingInterface
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var FieldSerializerInterface
     */
    private $fieldSerializer;

    /**
     * @param string                        $name
     * @param FieldSerializerInterface|null $fieldSerializer
     */
    public function __construct(string $name, FieldSerializerInterface $fieldSerializer = null)
    {
        $this->name = $name;
        $this->fieldSerializer = $fieldSerializer ?? new PropertySerializer($name);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return FieldSerializerInterface
     */
    public function getFieldSerializer(): FieldSerializerInterface
    {
        return $this->fieldSerializer;
    }
}
