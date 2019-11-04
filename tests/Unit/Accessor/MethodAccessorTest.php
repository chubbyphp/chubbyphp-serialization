<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization\Unit\Accessor;

use Chubbyphp\Serialization\Accessor\MethodAccessor;
use Chubbyphp\Serialization\SerializerLogicException;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Chubbyphp\Serialization\Accessor\MethodAccessor
 *
 * @internal
 */
final class MethodAccessorTest extends TestCase
{
    public function testGetValue(): void
    {
        $object = new class() {
            /**
             * @var string
             */
            private $name;

            public function getName(): string
            {
                return $this->name;
            }

            public function setName(string $name): void
            {
                $this->name = $name;
            }
        };

        $object->setName('Name');

        $accessor = new MethodAccessor('name');

        self::assertSame('Name', $accessor->getValue($object));
    }

    public function testHasValue(): void
    {
        $object = new class() {
            /**
             * @var string
             */
            private $name;

            public function hasName(): bool
            {
                return (bool) $this->name;
            }

            public function setName(string $name): void
            {
                $this->name = $name;
            }
        };

        $object->setName('Name');

        $accessor = new MethodAccessor('name');

        self::assertTrue($accessor->getValue($object));
    }

    public function testIsValue(): void
    {
        $object = new class() {
            /**
             * @var string
             */
            private $name;

            public function isName(): bool
            {
                return (bool) $this->name;
            }

            public function setName(string $name): void
            {
                $this->name = $name;
            }
        };

        $object->setName('Name');

        $accessor = new MethodAccessor('name');

        self::assertTrue($accessor->getValue($object));
    }

    public function testMissingGet(): void
    {
        $this->expectException(SerializerLogicException::class);

        $object = new class() {
        };

        $accessor = new MethodAccessor('name');
        $accessor->getValue($object);
    }
}
