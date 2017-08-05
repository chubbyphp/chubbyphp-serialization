<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization\Link;

use Chubbyphp\Serialization\Link\Link;

/**
 * @covers \Chubbyphp\Serialization\Link\Link
 */
class LinkTest extends \PHPUnit_Framework_TestCase
{
    public function testGetValue()
    {
        $link = new Link('http://test.com/models/id1', Link::METHOD_GET, ['key' => 'value']);

        self::assertEquals([
            'href' => 'http://test.com/models/id1',
            'method' => 'GET',
            'attributes' => ['key' => 'value'],
        ], $link->jsonSerialize());
    }
}
