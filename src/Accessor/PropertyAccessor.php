<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Accessor;

final class PropertyAccessor implements AccessorInterface
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
     * @param object $object
     *
     * @return mixed
     */
    public function getValue($object)
    {
        $reflectionProperty = new \ReflectionProperty(get_class($object), $this->property);
        $reflectionProperty->setAccessible(true);

        return $reflectionProperty->getValue($object);
    }
}
