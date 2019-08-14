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
     * @param array  $data
     * @param string $contentType
     *
     * @throws SerializerLogicException
     *
     * @return string
     */
    public function encode(array $data, string $contentType): string;
}
