<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization\Serializer\Link;

use Chubbyphp\Serialization\Link\LinkInterface;
use Chubbyphp\Serialization\Serializer\Link\CallbackLinkSerializer;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * @covers \Chubbyphp\Serialization\Serializer\Link\CallbackLinkSerializer
 */
class CallbackLinkSerializerTest extends \PHPUnit_Framework_TestCase
{
    public function testSerializeLink()
    {
        $expectedRequest = $this->getRequest();
        $expectedObject = new \stdClass();
        $expectedFields = [];
        $expectedLink = $this->getLink();

        $serializer = new CallbackLinkSerializer(
            function (
                Request $request,
                $object,
                array $fields
            ) use ($expectedRequest, $expectedObject, $expectedFields, $expectedLink) {
                self::assertSame($expectedRequest, $request);
                self::assertSame($expectedObject, $object);
                self::assertSame($expectedFields, $fields);

                return $expectedLink;
            }
        );

        self::assertSame(
            $expectedLink,
            $serializer->serializeLink('', $expectedRequest, $expectedObject, $expectedFields)
        );
    }

    /**
     * @return Request
     */
    private function getRequest(): Request
    {
        return $this->getMockBuilder(Request::class)->getMockForAbstractClass();
    }

    /**
     * @return LinkInterface
     */
    private function getLink(): LinkInterface
    {
        return $this->getMockBuilder(LinkInterface::class)->getMockForAbstractClass();
    }
}
