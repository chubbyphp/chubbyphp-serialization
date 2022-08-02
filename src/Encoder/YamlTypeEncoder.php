<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Encoder;

use Chubbyphp\DecodeEncode\Encoder\YamlTypeEncoder as BaseYamlTypeEncoder;

/**
 * @deprecated use \Chubbyphp\DecodeEncode\Encoder\YamlTypeEncoder
 */
final class YamlTypeEncoder implements TypeEncoderInterface
{
    private BaseYamlTypeEncoder $yamlTypeEncoder;

    public function __construct()
    {
        $this->yamlTypeEncoder = new BaseYamlTypeEncoder();
    }

    public function getContentType(): string
    {
        @trigger_error(
            sprintf(
                '%s:getContentType use %s:getContentType',
                self::class,
                BaseYamlTypeEncoder::class
            ),
            E_USER_DEPRECATED
        );

        return $this->yamlTypeEncoder->getContentType();
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
                BaseYamlTypeEncoder::class
            ),
            E_USER_DEPRECATED
        );

        return $this->yamlTypeEncoder->encode($data);
    }
}
