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

    public function __construct(NormalizerInterface $normalizer, EncoderInterface $encoder)
    {
        $this->normalizer = $normalizer;
        $this->encoder = $encoder;
    }

    /**
     * @param object $object
     * @param string $path
     */
    public function serialize(
        $object,
        string $contentType,
        NormalizerContextInterface $context = null,
        $path = ''
    ): string {
        return $this->encoder->encode($this->normalizer->normalize($object, $context, $path), $contentType);
    }

    /**
     * @param object $object
     *
     * @throws SerializerLogicException
     *
     * @return array<mixed>
     */
    public function normalize($object, NormalizerContextInterface $context = null, string $path = ''): array
    {
        return $this->normalizer->normalize($object, $context, $path);
    }

    /**
     * @return array<int, string>
     */
    public function getContentTypes(): array
    {
        return $this->encoder->getContentTypes();
    }

    /**
     * @param array<mixed> $data
     *
     * @throws SerializerLogicException
     */
    public function encode(array $data, string $contentType): string
    {
        return $this->encoder->encode($data, $contentType);
    }
}
