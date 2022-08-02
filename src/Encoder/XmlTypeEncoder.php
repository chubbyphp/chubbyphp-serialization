<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Encoder;

use Chubbyphp\DecodeEncode\Encoder\XmlTypeEncoder as BaseXmlTypeEncoder;

/**
 * @deprecated use \Chubbyphp\DecodeEncode\Encoder\XmlTypeEncoder
 */
final class XmlTypeEncoder implements TypeEncoderInterface
{
    private BaseXmlTypeEncoder $xmlTypeEncoder;

    public function __construct(bool $prettyPrint = false)
    {
        $this->xmlTypeEncoder = new BaseXmlTypeEncoder($prettyPrint);
    }

    public function getContentType(): string
    {
        @trigger_error(
            sprintf(
                '%s:getContentType use %s:getContentType',
                self::class,
                BaseXmlTypeEncoder::class
            ),
            E_USER_DEPRECATED
        );

        return $this->xmlTypeEncoder->getContentType();
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
                BaseXmlTypeEncoder::class
            ),
            E_USER_DEPRECATED
        );

        return $this->xmlTypeEncoder->encode($data);
    }
}
