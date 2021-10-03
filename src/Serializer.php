<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization;

use Chubbyphp\Serialization\Encoder\EncoderInterface;
use Chubbyphp\Serialization\Normalizer\NormalizerContextInterface;
use Chubbyphp\Serialization\Normalizer\NormalizerInterface;

final class Serializer implements SerializerInterface
{
    private NormalizerInterface $normalizer;

    private EncoderInterface $encoder;

    public function __construct(NormalizerInterface $normalizer, EncoderInterface $encoder)
    {
        $this->normalizer = $normalizer;
        $this->encoder = $encoder;
    }

    public function serialize(
        object $object,
        string $contentType,
        ?NormalizerContextInterface $context = null,
        string $path = ''
    ): string {
        return $this->encoder->encode($this->normalizer->normalize($object, $context, $path), $contentType);
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
     *
     * @throws SerializerLogicException
     */
    public function encode(array $data, string $contentType): string
    {
        return $this->encoder->encode($data, $contentType);
    }
}
