<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Formatter;

interface FormatterInterface
{
    /**
     * @param array $data
     *
     * @return string
     */
    public function format(array $data): string;
}
