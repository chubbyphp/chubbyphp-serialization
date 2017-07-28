<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization;

final class NotObjectException extends \InvalidArgumentException
{
    /**
     * @param string $type
     *
     * @return self
     */
    public static function createByType(string $type): self
    {
        return new self(sprintf('Input is not an object, type %s given', $type));
    }
}
