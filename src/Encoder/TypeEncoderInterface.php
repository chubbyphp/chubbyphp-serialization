<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Encoder;

interface TypeEncoderInterface
{
    /**
     * @return string
     */
    public function getContentType(): string;

    /**
     * @param array $data
     *
     * @return string
     */
    public function encode(array $data): string;
}
