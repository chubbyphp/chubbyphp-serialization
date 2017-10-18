<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization;

final class SerializerLogicException extends \LogicException
{
    /**
     * @param string $contentType
     *
     * @return self
     */
    public static function createMissingContentType(string $contentType): self
    {
        return new self(sprintf('There is no encoder for content-type: "%s"', $contentType));
    }

    /**
     * @param string $path
     *
     * @return self
     */
    public static function createMissingNormalizer(string $path): self
    {
        return new self(sprintf('There is no normalizer at path: "%s"', $path));
    }

    /**
     * @param string $class
     *
     * @return self
     */
    public static function createMissingMapping(string $class): self
    {
        return new self(sprintf('There is no mapping for class: "%s"', $class));
    }

    /**
     * @param string $class
     * @param array  $methods
     *
     * @return self
     */
    public static function createMissingMethod(string $class, array $methods): self
    {
        return new self(
            sprintf('There are no accessible method(s) "%s", within class: "%s"', implode('", "', $methods), $class)
        );
    }

    /**
     * @param string $class
     * @param string $property
     *
     * @return self
     */
    public static function createMissingProperty(string $class, string $property): self
    {
        return new self(sprintf('There is no property "%s" within class: "%s"', $property, $class));
    }
}
