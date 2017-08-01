<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Accessor;

final class MethodAccessor implements AccessorInterface
{
    /**
     * @var string
     */
    private $method;

    /**
     * @param string $method
     */
    public function __construct($method)
    {
        $this->method = $method;
    }

    /**
     * @param object $object
     *
     * @return mixed
     */
    public function getValue($object)
    {
        $method = $this->method;

        return $object->$method();
    }
}
