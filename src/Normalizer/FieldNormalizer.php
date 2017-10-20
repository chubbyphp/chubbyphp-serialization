<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Normalizer;

use Chubbyphp\Serialization\Accessor\AccessorInterface;
use Psr\Http\Message\ServerRequestInterface as Request;

final class FieldNormalizer implements FieldNormalizerInterface
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
     */
    public function normalizeField(
        string $path,
        Request $request,
        $object,
        NormalizerContextInterface $context,
        NormalizerInterface $normalizer = null
    ) {
        return $this->accessor->getValue($object);
    }
}
