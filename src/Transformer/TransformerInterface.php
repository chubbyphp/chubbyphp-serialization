<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Transformer;

interface TransformerInterface
{
    /**
     * @param array $data
     *
     * @return string
     */
    public function transform(array $data): string;
}
