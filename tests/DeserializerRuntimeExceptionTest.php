<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization;

use Chubbyphp\Serialization\SerializerRuntimeException;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Chubbyphp\Serialization\SerializerRuntimeException
 */
class SerializerRuntimeExceptionTest extends TestCase
{
    public function testCreateInvalidDataType()
    {
        $exception = SerializerRuntimeException::createInvalidDataType('path1', 'null', 'array');

        self::assertSame('There is an invalid data type "null", needed "array" at path: "path1"', $exception->getMessage());
    }

    public function testCreateNotParsable()
    {
        $exception = SerializerRuntimeException::createNotParsable('application/json');

        self::assertSame('Data is not parsable with content-type: "application/json"', $exception->getMessage());
    }

    public function testCreateNotAllowedAddtionalFields()
    {
        $exception = SerializerRuntimeException::createNotAllowedAdditionalFields(['path1', 'path2']);

        self::assertSame('There are additional field(s) at paths: "path1", "path2"', $exception->getMessage());
    }

    public function testCreateMissingObjectType()
    {
        $exception = SerializerRuntimeException::createMissingObjectType('path1', ['model']);

        self::assertSame('Missing object type, supported are "model" at path: "path1"', $exception->getMessage());
    }

    public function testCreateInvalidObjectType()
    {
        $exception = SerializerRuntimeException::createInvalidObjectType('path1', 'unknown', ['model']);

        self::assertSame('Unsupported object type "unknown", supported are "model" at path: "path1"', $exception->getMessage());
    }
}
