<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization\Unit\Accessor;

use Chubbyphp\Serialization\Accessor\PropertyAccessor;
use Chubbyphp\Serialization\SerializerLogicException;
use Doctrine\Persistence\Proxy;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Chubbyphp\Serialization\Accessor\PropertyAccessor
 *
 * @internal
 */
final class PropertyAccessorTest extends TestCase
{
    public function testGetValue(): void
    {
        $object = new Model();

        $object->setName('Name');

        $accessor = new PropertyAccessor('name');

        self::assertSame('Name', $accessor->getValue($object));
    }

    public function testGetValueCanAccessPrivatePropertyThroughDoctrineProxyClass(): void
    {
        $object = new class() extends Model implements Proxy {
            public function __load(): void
            {
            }

            public function __isInitialized(): bool
            {
                return false;
            }
        };

        $object->setName('Name');

        $accessor = new PropertyAccessor('name');

        self::assertSame('Name', $accessor->getValue($object));
    }

    public function testMissingGet(): void
    {
        $this->expectException(SerializerLogicException::class);

        $object = new class() {
        };

        $accessor = new PropertyAccessor('name');
        $accessor->getValue($object);
    }
}

class Model
{
    protected ?string $name = null;

    public function setName(string $name): void
    {
        $this->name = $name;
    }
}
