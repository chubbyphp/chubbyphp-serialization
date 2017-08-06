<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization\Mapping;

use Chubbyphp\Serialization\Mapping\LinkMapping;
use Chubbyphp\Serialization\Serializer\Link\LinkSerializerInterface;

/**
 * @covers \Chubbyphp\Serialization\Mapping\LinkMapping
 */
final class LinkMappingTest extends \PHPUnit_Framework_TestCase
{
    public function test()
    {
        $name = 'link1';
        $linkSerializer = $this->getLinkSerializer();

        $linkMapping = new LinkMapping($name, $linkSerializer);

        self::assertSame($name, $linkMapping->getName());
        self::assertSame($linkSerializer, $linkMapping->getLinkSerializer());
    }

    /**
     * @return LinkSerializerInterface
     */
    private function getLinkSerializer(): LinkSerializerInterface
    {
        return $this->getMockBuilder(LinkSerializerInterface::class)->getMockForAbstractClass();
    }
}
