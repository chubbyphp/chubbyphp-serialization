<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization\Unit\Accessor;

use Chubbyphp\Serialization\Accessor\PropertyAccessor;
use Chubbyphp\Serialization\SerializerLogicException;
use Doctrine\Common\Persistence\Proxy;
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

            /**
             * @return bool
             */
            public function __isInitialized()
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
    /**
     * @var string
     */
    protected $name;

    public function setName(string $name): void
    {
        $this->name = $name;
    }
}
