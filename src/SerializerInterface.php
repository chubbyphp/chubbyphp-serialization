<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization;

use Chubbyphp\Serialization\Encoder\EncoderInterface;
use Chubbyphp\Serialization\Normalizer\NormalizerContextInterface;
use Chubbyphp\Serialization\Normalizer\NormalizerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;

interface SerializerInterface extends NormalizerInterface, EncoderInterface
{
    /**
     * @param Request                    $request
     * @param object                     $object
     * @param string                     $contentType
     * @param NormalizerContextInterface $context
     * @param string                     $path
     *
     * @return string
     */
    public function serialize(
        Request $request,
        $object,
        string $contentType,
        NormalizerContextInterface $context = null,
        $path = ''
    ): string;
}
