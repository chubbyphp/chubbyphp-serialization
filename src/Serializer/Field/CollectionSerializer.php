<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Serializer\Field;

use Chubbyphp\Serialization\Accessor\AccessorInterface;
use Chubbyphp\Serialization\SerializerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;

final class CollectionSerializer implements FieldSerializerInterface
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
        $collection = [];
        foreach ($this->accessor->getValue($object) as $i => $childObject) {
            $subPath = $path.'.['.$i.']';
            $collection[$i] = $serializer->serialize($request, $childObject, $subPath);
        }

        return $collection;
    }
}
