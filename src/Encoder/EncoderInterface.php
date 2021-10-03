<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Encoder;

use Chubbyphp\Serialization\SerializerLogicException;

interface EncoderInterface
{
    /**
     * @return array<int, string>
     */
    public function getContentTypes(): array;

    /**
     * @param array<string, null|array|bool|float|int|string> $data
     *
     * @throws SerializerLogicException
     */
    public function encode(array $data, string $contentType): string;
}
