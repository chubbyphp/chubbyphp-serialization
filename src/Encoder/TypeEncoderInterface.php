<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Encoder;

interface TypeEncoderInterface
{
    public function getContentType(): string;

    public function encode(array $data): string;
}
