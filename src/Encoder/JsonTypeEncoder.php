<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Encoder;

final class JsonTypeEncoder implements TypeEncoderInterface
{
    /**
     * @var bool
     */
    private $prettyPrint;

    /**
     * @var bool
     */
    private $ignoreInvalidUtf8;

    public function __construct(bool $prettyPrint = false, bool $ignoreInvalidUtf8 = false)
    {
        $this->prettyPrint = $prettyPrint;
        $this->ignoreInvalidUtf8 = $ignoreInvalidUtf8;
    }

    public function getContentType(): string
    {
        return 'application/json';
    }

    /**
     * @param array<mixed> $data
     */
    public function encode(array $data): string
    {
        $options = JSON_PRESERVE_ZERO_FRACTION | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES;
        if ($this->prettyPrint) {
            $options |= JSON_PRETTY_PRINT;
        }
        if ($this->ignoreInvalidUtf8) {
            $options |= JSON_INVALID_UTF8_IGNORE;
        }

        return json_encode($data, $options);
    }
}
