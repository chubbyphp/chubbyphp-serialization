<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization;

final class NotObjectException extends \InvalidArgumentException
{
    /**
     * @param string $type
     *
     * @return self
     *
     * @deprecated use createByTypeAndPath
     */
    public static function createByType(string $type): self
    {
        return new self(sprintf('Input is not an object, type %s given', $type));
    }

    /**
     * @param string $type
     * @param string $path
     *
     * @return self
     */
    public static function createByTypeAndPath(string $type, string $path): self
    {
        return new self(sprintf('Input is not an object, type %s given at path %s', $type, $path));
    }
}
