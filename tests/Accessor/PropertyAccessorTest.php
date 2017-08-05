<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization\Accessor;

use Chubbyphp\Serialization\Accessor\PropertyAccessor;
use Chubbyphp\Tests\Serialization\Resources\Item;

/**
 * @covers \Chubbyphp\Serialization\Accessor\PropertyAccessor
 */
class PropertyAccessorTest extends \PHPUnit_Framework_TestCase
{
    public function testGetValue()
    {
        $item = new Item('id1');
        $item->setName('name1');

        $accessor = new PropertyAccessor('name');

        self::assertSame('name1', $accessor->getValue($item));
    }
}
