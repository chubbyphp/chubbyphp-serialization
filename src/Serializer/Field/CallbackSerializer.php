<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Serializer\Field;

use Chubbyphp\Serialization\SerializerInterface;

final class CallbackSerializer implements FieldSerializerInterface
{
    /**
     * @var callable
     */
    private $callback;

    /**
     * @return callable
     */
    public function getCallback(): callable
    {
        return $this->callback;
    }

    /**
     * @param callable $callback
     */
    public function setCallback(callable $callback)
    {
        $this->callback = $callback;
    }

    /**
     * @param string $path
     * @param object $object
     * @param SerializerInterface|null $serializer
     * @return mixed
     */
    public function serializeField(string $path, $object, SerializerInterface $serializer = null)
    {
        $callback = $this->callback;

        return $callback($object, $serializer);
    }
}
