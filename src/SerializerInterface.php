<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization;

use Chubbyphp\Serialization\Encoder\EncoderInterface;
use Chubbyphp\Serialization\Normalizer\NormalizerContextInterface;
use Chubbyphp\Serialization\Normalizer\NormalizerInterface;

interface SerializerInterface extends EncoderInterface, NormalizerInterface
{
    public function serialize(
        object $object,
        string $contentType,
        ?NormalizerContextInterface $context = null,
        string $path = ''
    ): string;
}
