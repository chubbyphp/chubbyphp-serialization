<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization\Link;

use Chubbyphp\Serialization\Link\LinkBuilder;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Chubbyphp\Serialization\Link\LinkBuilder
 *
 * @internal
 */
class LinkBuilderTest extends TestCase
{
    public function testSetters()
    {
        $link = LinkBuilder::create('/api/models/{id}')
            ->setRels(['models'])
            ->setAttributes(['method' => 'GET'])
            ->getLink()
        ;

        self::assertSame('/api/models/{id}', $link->getHref());
        self::assertTrue($link->isTemplated());
        self::assertSame(['models'], $link->getRels());
        self::assertSame(['method' => 'GET'], $link->getAttributes());
    }

    public function testDefault()
    {
        $link = LinkBuilder::create('/api/models')->getLink();

        self::assertSame('/api/models', $link->getHref());
        self::assertFalse($link->isTemplated());
        self::assertSame([], $link->getRels());
        self::assertSame([], $link->getAttributes());
    }
}
