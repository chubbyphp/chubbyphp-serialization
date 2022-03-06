<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Accessor;

use Chubbyphp\Serialization\SerializerLogicException;
use Doctrine\Persistence\Proxy;

final class PropertyAccessor implements AccessorInterface
{
    public function __construct(private string $property)
    {
    }

    /**
     * @return mixed
     */
    public function getValue(object $object)
    {
        $class = $this->getClass($object);

        if (!property_exists($class, $this->property)) {
            throw SerializerLogicException::createMissingProperty($class, $this->property);
        }

        $getter = \Closure::bind(
            fn ($property) => $this->{$property},
            $object,
            $class
        );

        return $getter($this->property);
    }

    private function getClass(object $object): string
    {
        if (interface_exists('Doctrine\Persistence\Proxy') && $object instanceof Proxy) {
            if (!$object->__isInitialized()) {
                $object->__load();
            }

            $reflectionParentClass = (new \ReflectionObject($object))->getParentClass();
            if ($reflectionParentClass instanceof \ReflectionClass) {
                return $reflectionParentClass->getName();
            }
        }

        return $object::class;
    }
}
