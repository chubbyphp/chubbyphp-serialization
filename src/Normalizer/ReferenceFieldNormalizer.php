<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Normalizer;

use Chubbyphp\Serialization\Accessor\AccessorInterface;
use Chubbyphp\Serialization\SerializerLogicException;
use Psr\Http\Message\ServerRequestInterface as Request;

final class ReferenceFieldNormalizer implements FieldNormalizerInterface
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
     * @return array
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

        if (null === $value = $this->accessor->getValue($object)) {
            return null;
        }

        return $normalizer->normalize($request, $value, $context, $path);
    }
}
