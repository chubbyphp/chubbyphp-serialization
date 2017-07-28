<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Data;

interface KeyValueInterface
{
    /**
     * @return string
     */
    public function getKey(): string;

    /**
     * @return mixed
     */
    public function getValue();
}
