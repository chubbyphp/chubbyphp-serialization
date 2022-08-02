<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Encoder;

use Chubbyphp\DecodeEncode\Encoder\Encoder as BaseEncoder;
use Chubbyphp\DecodeEncode\Encoder\EncoderInterface as BaseEncoderInterface;
use Chubbyphp\DecodeEncode\LogicException;
use Chubbyphp\Serialization\SerializerLogicException;

/**
 * @deprecated use \Chubbyphp\DecodeEncode\Encoder\Encoder
 */
final class Encoder implements EncoderInterface
{
    private BaseEncoderInterface $encoder;

    /**
     * @param array<int, TypeEncoderInterface> $encoderTypes
     */
    public function __construct(array $encoderTypes)
    {
        $this->encoder = new BaseEncoder($encoderTypes);
    }

    /**
     * @return array<int, string>
     */
    public function getContentTypes(): array
    {
        @trigger_error(
            sprintf(
                '%s:getContentTypes use %s:getContentTypes',
                self::class,
                BaseEncoder::class
            ),
            E_USER_DEPRECATED
        );

        return $this->encoder->getContentTypes();
    }

    /**
     * @param array<string, null|array|bool|float|int|string> $data
     *
     * @throws SerializerLogicException
     */
    public function encode(array $data, string $contentType): string
    {
        @trigger_error(
            sprintf(
                '%s:encode use %s:encode',
                self::class,
                BaseEncoder::class
            ),
            E_USER_DEPRECATED
        );

        try {
            return $this->encoder->encode($data, $contentType);
        } catch (LogicException $e) {
            throw new SerializerLogicException($e->getMessage(), $e->getCode(), $e);
        }
    }
}
