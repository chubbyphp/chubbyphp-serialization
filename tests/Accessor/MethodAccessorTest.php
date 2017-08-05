<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization\Accessor;

use Chubbyphp\Serialization\Accessor\MethodAccessor;
use Chubbyphp\Tests\Serialization\Resources\Item;

/**
 * @covers \Chubbyphp\Serialization\Accessor\MethodAccessor
 */
class MethodAccessorTest extends \PHPUnit_Framework_TestCase
{
    public function testGetValue()
    {
        $item = new Item('id1');
        $item->setName('name1');

        $accessor = new MethodAccessor('getName');

        self::assertSame('name1', $accessor->getValue($item));
    }
}
