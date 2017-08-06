<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization;

use Chubbyphp\Serialization\NotObjectException;

/**
 * @covers \Chubbyphp\Serialization\NotObjectException
 */
class NotObjectExceptionTest extends \PHPUnit_Framework_TestCase
{
    public function testCreate()
    {
        $exception = NotObjectException::createByType('string');

        self::assertSame('Input is not an object, type string given', $exception->getMessage());
    }
}
