<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Serializer\Field;

use Chubbyphp\Serialization\SerializerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;

interface FieldSerializerInterface
{
    /**
     * @param string                   $path
     * @param Request                  $request
     * @param object                   $object
     * @param SerializerInterface|null $serializer
     *
     * @return mixed
     */
    public function serializeField(string $path, Request $request, $object, SerializerInterface $serializer = null);
}
