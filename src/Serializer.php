<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization;

use Chubbyphp\DecodeEncode\Encoder\EncoderInterface;
use Chubbyphp\Serialization\Normalizer\NormalizerContextInterface;
use Chubbyphp\Serialization\Normalizer\NormalizerInterface;

final class Serializer implements SerializerInterface
{
    public function __construct(private NormalizerInterface $normalizer, private EncoderInterface $encoder) {}

    public function serialize(
        object $object,
        string $contentType,
        ?NormalizerContextInterface $context = null,
        string $path = ''
    ): string {
        return $this->encode($this->normalize($object, $context, $path), $contentType);
    }

    /**
     * @return array<string, null|array|bool|float|int|string>
     */
    public function normalize(object $object, ?NormalizerContextInterface $context = null, string $path = ''): array
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
     * @param array<string, null|array|bool|float|int|string> $data
     */
    public function encode(array $data, string $contentType): string
    {
        return $this->encoder->encode($data, $contentType);
    }
}
