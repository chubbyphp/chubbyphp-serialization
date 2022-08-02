<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Encoder;

use Chubbyphp\DecodeEncode\Encoder\UrlEncodedTypeEncoder as BaseUrlEncodedTypeEncoder;

/**
 * @deprecated use \Chubbyphp\DecodeEncode\Encoder\UrlEncodedTypeEncoder
 */
final class UrlEncodedTypeEncoder implements TypeEncoderInterface
{
    private BaseUrlEncodedTypeEncoder $urlEncodedTypeEncoder;

    public function __construct()
    {
        $this->urlEncodedTypeEncoder = new BaseUrlEncodedTypeEncoder();
    }

    public function getContentType(): string
    {
        @trigger_error(
            sprintf(
                '%s:getContentType use %s:getContentType',
                self::class,
                BaseUrlEncodedTypeEncoder::class
            ),
            E_USER_DEPRECATED
        );

        return $this->urlEncodedTypeEncoder->getContentType();
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
                BaseUrlEncodedTypeEncoder::class
            ),
            E_USER_DEPRECATED
        );

        return $this->urlEncodedTypeEncoder->encode($data);
    }
}
