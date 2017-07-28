<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Serializer\Field;

use Chubbyphp\Serialization\SerializerInterface;

final class PropertySerializer implements FieldSerializerInterface
{
    /**
     * @var string
     */
    private $property;

    /**
     * @param string $property
     */
    public function __construct($property)
    {
        $this->property = $property;
    }

    /**
     * @param string $path
     * @param object $object
     * @param SerializerInterface|null $serializer
     * @return mixed
     */
    public function serializeField(string $path, $object, SerializerInterface $serializer = null)
    {
        $reflectionProperty = new \ReflectionProperty(get_class($object), $this->property);
        $reflectionProperty->setAccessible(true);

        return $reflectionProperty->getValue($object);
    }
}
