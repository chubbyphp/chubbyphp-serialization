<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization\Link;

use Chubbyphp\Serialization\Link\NullLink;

/**
 * @covers \Chubbyphp\Serialization\Link\NullLink
 */
class NullLinkTest extends \PHPUnit_Framework_TestCase
{
    public function testGetValue()
    {
        $link = new NullLink();

        self::assertEquals([], $link->jsonSerialize());
    }
}
