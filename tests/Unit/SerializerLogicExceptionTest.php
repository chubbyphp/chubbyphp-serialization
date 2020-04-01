<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization\Unit;

use Chubbyphp\Serialization\SerializerLogicException;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Chubbyphp\Serialization\SerializerLogicException
 *
 * @internal
 */
final class SerializerLogicExceptionTest extends TestCase
{
    public function testCreateMissingContentType(): void
    {
        $exception = SerializerLogicException::createMissingContentType('application/json');

        self::assertSame('There is no encoder for content-type: "application/json"', $exception->getMessage());
    }

    public function testCreateWrongDataType(): void
    {
        $exception = SerializerLogicException::createWrongDataType('path1', 'string');

        self::assertSame('Wrong data type "string" at path : "path1"', $exception->getMessage());
    }

    public function testCreateMissingNormalizer(): void
    {
        $exception = SerializerLogicException::createMissingNormalizer('path1');

        self::assertSame('There is no normalizer at path: "path1"', $exception->getMessage());
    }

    public function testCreateMissingMapping(): void
    {
        $exception = SerializerLogicException::createMissingMapping(\stdClass::class);

        self::assertSame('There is no mapping for class: "stdClass"', $exception->getMessage());
    }

    public function testCreateMissingMethod(): void
    {
        $exception = SerializerLogicException::createMissingMethod(\stdClass::class, ['getName', 'hasName']);

        self::assertSame(
            'There are no accessible method(s) "getName", "hasName", within class: "stdClass"',
            $exception->getMessage()
        );
    }

    public function testCreateNotParsable(): void
    {
        $exception = SerializerLogicException::createMissingProperty(\stdClass::class, 'name');

        self::assertSame('There is no property "name" within class: "stdClass"', $exception->getMessage());
    }

    public function testCreateInvalidLinkTypeReturned(): void
    {
        $exception = SerializerLogicException::createInvalidLinkTypeReturned('path1', 'string');

        self::assertSame(
            'The link normalizer callback needs to return a Psr\Link\LinkInterface|null, "string" given at path: "path1"',
            $exception->getMessage()
        );
    }

    public function testCreateDeprecatedMethod(): void
    {
        $exception = SerializerLogicException::createDeprecatedMethod(\stdClass::class, ['getName', 'hasName']);

        self::assertSame(
            'Method(s) "getName", "hasName", are deprecated within class: "stdClass"',
            $exception->getMessage()
        );
    }
}
