<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization\Accessor;

use Chubbyphp\Serialization\Accessor\MethodAccessor;
use Chubbyphp\Tests\Serialization\Resources\Model;

/**
 * @covers \Chubbyphp\Serialization\Accessor\MethodAccessor
 */
class MethodAccessorTest extends \PHPUnit_Framework_TestCase
{
    public function testGetValue()
    {
        $model = new Model('id1');
        $model->setName('name1');

        $accessor = new MethodAccessor('getName');

        self::assertSame('name1', $accessor->getValue($model));
    }
}
