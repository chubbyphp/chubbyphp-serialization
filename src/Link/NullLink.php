<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Link;

final class NullLink implements LinkInterface
{
    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [];
    }
}
