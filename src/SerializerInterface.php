<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization;

use Chubbyphp\Serialization\Encoder\EncoderInterface;
use Chubbyphp\Serialization\Normalizer\NormalizerContextInterface;
use Chubbyphp\Serialization\Normalizer\NormalizerInterface;

interface SerializerInterface extends NormalizerInterface, EncoderInterface
{
    /**
     * @param object                          $object
     * @param string                          $contentType
     * @param NormalizerContextInterface|null $context
     * @param string                          $path
     *
     * @return string
     */
    public function serialize(
        $object,
        string $contentType,
        NormalizerContextInterface $context = null,
        $path = ''
    ): string;
}
