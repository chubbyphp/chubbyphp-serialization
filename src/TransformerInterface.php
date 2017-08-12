<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization;

interface TransformerInterface
{
    /**
     * @return string[]
     */
    public function getContentTypes(): array;

    /**
     * @param array  $data
     * @param string $contentType
     *
     * @return string
     *
     * @throws \InvalidArgumentException
     */
    public function transform(array $data, string $contentType): string;
}
