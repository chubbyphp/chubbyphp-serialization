<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Normalizer;

use Chubbyphp\Serialization\SerializerLogicException;
use Psr\Http\Message\ServerRequestInterface as Request;

interface FieldNormalizerInterface
{
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
    );
}
