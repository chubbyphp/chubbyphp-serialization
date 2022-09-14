<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Encoder;

use Chubbyphp\DecodeEncode\Encoder\JsonxTypeEncoder as BaseJsonxTypeEncoder;

/**
 * @deprecated use \Chubbyphp\DecodeEncode\Encoder\JsonxTypeEncoder
 */
final class JsonxTypeEncoder implements TypeEncoderInterface
{
    public const DATATYPE_OBJECT = BaseJsonxTypeEncoder::DATATYPE_OBJECT;
    public const DATATYPE_ARRAY = BaseJsonxTypeEncoder::DATATYPE_ARRAY;
    public const DATATYPE_BOOLEAN = BaseJsonxTypeEncoder::DATATYPE_BOOLEAN;
    public const DATATYPE_STRING = BaseJsonxTypeEncoder::DATATYPE_STRING;
    public const DATATYPE_NUMBER = BaseJsonxTypeEncoder::DATATYPE_NUMBER;
    public const DATATYPE_NULL = BaseJsonxTypeEncoder::DATATYPE_NULL;

    private BaseJsonxTypeEncoder $jsonxTypeEncoder;

    public function __construct(bool $prettyPrint = false)
    {
        $this->jsonxTypeEncoder = new BaseJsonxTypeEncoder($prettyPrint);
    }

    public function getContentType(): string
    {
        @trigger_error(
            sprintf(
                '%s:getContentType use %s:getContentType',
                self::class,
                BaseJsonxTypeEncoder::class
            ),
            E_USER_DEPRECATED
        );

        return $this->jsonxTypeEncoder->getContentType();
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
                BaseJsonxTypeEncoder::class
            ),
            E_USER_DEPRECATED
        );

        return $this->jsonxTypeEncoder->encode($data);
    }
}
