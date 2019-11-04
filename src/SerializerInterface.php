<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization;

use Chubbyphp\Serialization\Encoder\EncoderInterface;
use Chubbyphp\Serialization\Normalizer\NormalizerContextInterface;
use Chubbyphp\Serialization\Normalizer\NormalizerInterface;

interface SerializerInterface extends EncoderInterface, NormalizerInterface
{
    /**
     * @param object $object
     * @param string $path
     */
    public function serialize(
        $object,
        string $contentType,
        NormalizerContextInterface $context = null,
        $path = ''
    ): string;
}
