<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Serializer\Field;

use Chubbyphp\Serialization\SerializerInterface;

final class MethodSerializer implements FieldSerializerInterface
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
     * @param string                   $path
     * @param object                   $object
     * @param SerializerInterface|null $serializer
     *
     * @return mixed
     */
    public function serializeField(string $path, $object, SerializerInterface $serializer = null)
    {
        $method = $this->method;

        return $object->$method();
    }
}
