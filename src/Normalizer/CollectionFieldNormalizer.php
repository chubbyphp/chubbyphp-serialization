<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Normalizer;

use Chubbyphp\Serialization\Accessor\AccessorInterface;
use Chubbyphp\Serialization\SerializerLogicException;
use Psr\Http\Message\ServerRequestInterface as Request;

final class CollectionFieldNormalizer implements FieldNormalizerInterface
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
     * @param string                     $path
     * @param Request                    $request
     * @param object                     $object
     * @param NormalizerContextInterface $context
     * @param NormalizerInterface|null   $normalizer
     *
     * @return mixed
     *
     * @throws SerializerLogicException
     */
    public function normalizeField(
        string $path,
        Request $request,
        $object,
        NormalizerContextInterface $context,
        NormalizerInterface $normalizer = null
    ) {
        if (null === $normalizer) {
            throw SerializerLogicException::createMissingNormalizer($path);
        }

        $data = [];
        foreach ($this->accessor->getValue($object) as $i => $childObject) {
            $subPath = $path.'['.$i.']';
            $data[$i] = $normalizer->normalize($request, $childObject, $context, $subPath);
        }

        return $data;
    }
}
