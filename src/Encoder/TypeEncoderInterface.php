<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Encoder;

interface TypeEncoderInterface
{
    public function getContentType(): string;

    /**
     * @param array<string, array|string|float|int|bool|null> $data
     */
    public function encode(array $data): string;
}
