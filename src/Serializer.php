<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization;

use Chubbyphp\Serialization\Encoder\EncoderInterface;
use Chubbyphp\Serialization\Normalizer\NormalizerContextInterface;
use Chubbyphp\Serialization\Normalizer\NormalizerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;

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
    ): string {
        return $this->encoder->encode($this->normalizer->normalize($request, $object, $context, $path), $contentType);
    }

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
    public function normalize(Request $request, $object, NormalizerContextInterface $context = null, string $path = ''): array
    {
        return $this->normalizer->normalize($request, $object, $context, $path);
    }

    /**
     * @return string[]
     */
    public function getContentTypes(): array
    {
        return $this->encoder->getContentTypes();
    }

    /**
     * @param array  $data
     * @param string $contentType
     *
     * @return string
     *
     * @throws SerializerLogicException
     */
    public function encode(array $data, string $contentType): string
    {
        return $this->encoder->encode($data, $contentType);
    }
}
