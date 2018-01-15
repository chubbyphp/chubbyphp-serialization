<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Accessor;

use Chubbyphp\Serialization\SerializerLogicException;
use Doctrine\Common\Persistence\Proxy;

final class PropertyAccessor implements AccessorInterface
{
    /**
     * @var string
     */
    private $property;

    /**
     * @param string $property
     */
    public function __construct(string $property)
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
        if (true === class_exists('Doctrine\Common\Persistence\Proxy') && true === $object instanceof Proxy) {
            $class = (new \ReflectionClass($object))->getParentClass()->name;
        } else {
            $class = get_class($object);
        }

        try {
            $reflectionProperty = new \ReflectionProperty($class, $this->property);
        } catch (\ReflectionException $e) {
            throw SerializerLogicException::createMissingProperty($class, $this->property);
        }

        $reflectionProperty->setAccessible(true);

        return $reflectionProperty->getValue($object);
    }
}
