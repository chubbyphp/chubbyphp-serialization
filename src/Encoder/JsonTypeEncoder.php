<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Encoder;

use Chubbyphp\DecodeEncode\Encoder\JsonTypeEncoder as BaseJsonTypeEncoder;

/**
 * @deprecated use \Chubbyphp\DecodeEncode\Encoder\JsonTypeEncoder
 */
final class JsonTypeEncoder implements TypeEncoderInterface
{
    private BaseJsonTypeEncoder $jsonTypeEncoder;

    public function __construct(bool $prettyPrint = false)
    {
        $this->jsonTypeEncoder = new BaseJsonTypeEncoder($prettyPrint);
    }

    public function getContentType(): string
    {
        @trigger_error(
            sprintf(
                '%s:getContentType use %s:getContentType',
                self::class,
                BaseJsonTypeEncoder::class
            ),
            E_USER_DEPRECATED
        );

        return $this->jsonTypeEncoder->getContentType();
    }

    /**
     * @param array<string, null|array|bool|float|int|string> $data
     */
    public function encode(array $data): string
    {
        @trigger_error(
            sprintf(
                '%s:encode use %s:encode',
                self::class,
                BaseJsonTypeEncoder::class
            ),
            E_USER_DEPRECATED
        );

        return $this->jsonTypeEncoder->encode($data);
    }
}
