<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Normalizer;

use Psr\Http\Message\ServerRequestInterface as Request;

interface LinkNormalizerInterface
{
    /**
     * @param string                     $path
     * @param string                     $request
     * @param object                     $object
     * @param NormalizerContextInterface $context
     *
     * @return array
     */
    public function normalizeLink(string $path, Request $request, $object, NormalizerContextInterface $context);
}
