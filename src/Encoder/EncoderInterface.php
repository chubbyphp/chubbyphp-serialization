<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Encoder;

use Chubbyphp\Serialization\SerializerLogicException;

interface EncoderInterface
{
    /**
     * @return string[]
     */
    public function getContentTypes(): array;

    /**
     * @throws SerializerLogicException
     */
    public function encode(array $data, string $contentType): string;
}
