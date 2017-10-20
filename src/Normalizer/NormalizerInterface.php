<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Normalizer;

use Chubbyphp\Serialization\SerializerLogicException;
use Psr\Http\Message\ServerRequestInterface as Request;

interface NormalizerInterface
{
    /**
     * @param Request                         $request
     * @param object                          $object
     * @param NormalizerContextInterface|null $context
     * @param string                          $path
     *
     * @return array
     *
     * @throws SerializerLogicException
     */
    public function normalize(
        Request $request,
        $object,
        NormalizerContextInterface $context = null,
        string $path = ''
    ): array;
}
