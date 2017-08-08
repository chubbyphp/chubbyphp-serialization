<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Serializer\Field;

use Chubbyphp\Serialization\Accessor\AccessorInterface;
use Chubbyphp\Serialization\SerializerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;

final class CollectionFieldSerializer implements FieldSerializerInterface
{
    /**
     * @var AccessorInterface
     */
    private $accessor;

    /**
     * @param AccessorInterface $accessor
     */
    public function __construct(AccessorInterface $accessor)
    {
        $this->accessor = $accessor;
    }

    /**
     * @param string                   $path
     * @param Request                  $request
     * @param object                   $object
     * @param SerializerInterface|null $serializer
     *
     * @return mixed
     */
    public function serializeField(string $path, Request $request, $object, SerializerInterface $serializer = null)
    {
        $this->serializerOrException($serializer);

        $collection = [];
        foreach ($this->accessor->getValue($object) as $i => $childObject) {
            $subPath = $path.'['.$i.']';
            $collection[$i] = $serializer->serialize($request, $childObject, $subPath);
        }

        return $collection;
    }

    /**
     * @param SerializerInterface|null $serializer
     *
     * @throws \RuntimeException
     */
    private function serializerOrException(SerializerInterface $serializer = null)
    {
        if (null === $serializer) {
            throw new \RuntimeException(sprintf('Serializer needed: %s', SerializerInterface::class));
        }
    }
}
