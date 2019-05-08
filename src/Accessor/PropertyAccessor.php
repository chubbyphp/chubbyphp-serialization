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
        if (!property_exists($object, $this->property)) {
            throw SerializerLogicException::createMissingProperty(get_class($object), $this->property);
        }

        if ($object instanceof \stdClass) {
            return $object->{$this->property};
        }

        return \Closure::bind(
            function ($property) {
                return $this->$property;
            },
            $object,
            $this->getClass($object)
        )($this->property);
    }

    /**
     * @param object $object
     *
     * @return string
     */
    private function getClass($object): string
    {
        if (interface_exists('Doctrine\Common\Persistence\Proxy') && $object instanceof Proxy) {
            if (!$object->__isInitialized()) {
                $object->__load();
            }

            return (new \ReflectionClass($object))->getParentClass()->name;
        }

        return get_class($object);
    }
}
