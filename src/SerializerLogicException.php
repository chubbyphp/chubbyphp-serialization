<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization;

use Psr\Link\LinkInterface;

final class SerializerLogicException extends \LogicException
{
    public static function createMissingContentType(string $contentType): self
    {
        return new self(sprintf('There is no encoder for content-type: "%s"', $contentType));
    }

    /**
     * @return SerializerLogicException
     */
    public static function createWrongDataType(string $path, string $type): self
    {
        return new self(sprintf('Wrong data type "%s" at path : "%s"', $type, $path));
    }

    public static function createMissingNormalizer(string $path): self
    {
        return new self(sprintf('There is no normalizer at path: "%s"', $path));
    }

    public static function createMissingMapping(string $class): self
    {
        return new self(sprintf('There is no mapping for class: "%s"', $class));
    }

    public static function createMissingMethod(string $class, array $methods): self
    {
        return new self(
            sprintf('There are no accessible method(s) "%s", within class: "%s"', implode('", "', $methods), $class)
        );
    }

    public static function createMissingProperty(string $class, string $property): self
    {
        return new self(sprintf('There is no property "%s" within class: "%s"', $property, $class));
    }

    public static function createInvalidLinkTypeReturned(string $path, string $type): self
    {
        return new self(
            sprintf(
                'The link normalizer callback needs to return a %s|null, "%s" given at path: "%s"',
                LinkInterface::class,
                $type,
                $path
            )
        );
    }
}
