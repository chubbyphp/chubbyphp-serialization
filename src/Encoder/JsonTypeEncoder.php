<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Encoder;

final class JsonTypeEncoder implements TypeEncoderInterface
{
    /**
     * @var bool
     */
    private $prettyPrint;

    public function __construct(bool $prettyPrint = false)
    {
        $this->prettyPrint = $prettyPrint;
    }

    public function getContentType(): string
    {
        return 'application/json';
    }

    /**
     * @param array<string, array|string|float|int|bool|null> $data
     */
    public function encode(array $data): string
    {
        $options = JSON_PRESERVE_ZERO_FRACTION | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_INVALID_UTF8_IGNORE;
        if ($this->prettyPrint) {
            $options |= JSON_PRETTY_PRINT;
        }

        return json_encode($data, $options);
    }
}
