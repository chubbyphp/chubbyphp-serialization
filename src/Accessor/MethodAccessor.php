<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Accessor;

use Chubbyphp\Serialization\SerializerLogicException;

final class MethodAccessor implements AccessorInterface
{
    /**
     * @var string
     */
    private $property;

    public function __construct(string $property)
    {
        $this->property = $property;
    }

    /**
     * @param object $object
     *
     * @throws SerializerLogicException
     *
     * @return mixed
     */
    public function getValue($object)
    {
        $get = 'get'.ucfirst($this->property);
        $has = 'has'.ucfirst($this->property);
        $is = 'is'.ucfirst($this->property);

        if (method_exists($object, $get)) {
            return $object->{$get}();
        }

        if (method_exists($object, $has)) {
            return $object->{$has}();
        }

        if (method_exists($object, $is)) {
            return $object->{$is}();
        }

        throw SerializerLogicException::createMissingMethod(get_class($object), [$get, $has, $is]);
    }
}
