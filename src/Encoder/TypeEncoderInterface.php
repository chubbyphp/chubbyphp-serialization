<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Encoder;

interface TypeEncoderInterface
{
    public function getContentType(): string;

    /**
     * @param array<string, null|array|bool|float|int|string> $data
     */
    public function encode(array $data): string;
}
