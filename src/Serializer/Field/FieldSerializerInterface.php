<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Serializer\Field;

use Chubbyphp\Serialization\SerializerInterface;

interface FieldSerializerInterface
{
    /**
     * @param string $path
     * @param object $object
     * @param SerializerInterface|null $serializer
     * @return mixed
     */
    public function serializeField(string $path, $object, SerializerInterface $serializer = null);
}
