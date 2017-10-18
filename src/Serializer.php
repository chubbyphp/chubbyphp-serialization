<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization;

use Chubbyphp\Serialization\Encoder\EncoderInterface;
use Chubbyphp\Serialization\Normalizer\NormalizerContextInterface;
use Chubbyphp\Serialization\Normalizer\NormalizerInterface;

final class Serializer implements SerializerInterface
{
    /**
     * @var NormalizerInterface
     */
    private $normalizer;

    /**
     * @var EncoderInterface
     */
    private $encoder;

    /**
     * @param NormalizerInterface $normalizer
     * @param EncoderInterface    $encoder
     */
    public function __construct(NormalizerInterface $normalizer, EncoderInterface $encoder)
    {
        $this->normalizer = $normalizer;
        $this->encoder = $encoder;
    }

    /**
     * @param object                     $object
     * @param string                     $contentType
     * @param NormalizerContextInterface $context
     *
     * @return string
     */
    public function serialize($object, string $contentType, NormalizerContextInterface $context = null): string
    {
        return $this->encoder->encode($this->normalizer->normalize($object, $context));
    }
}
