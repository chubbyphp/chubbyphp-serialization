<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization\Serializer\Field;

use Chubbyphp\Serialization\Serializer\Field\CallbackFieldSerializer;
use Chubbyphp\Serialization\SerializerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;

class CallbackFieldSerializerTest extends \PHPUnit_Framework_TestCase
{
    public function testSerializeField()
    {
        $expectedPath = 'path';
        $expectedRequest = $this->getRequest();
        $expectedObject = new \stdClass();
        $expectedSerializer = $this->getSerializer();
        $expectedValue = 'value';

        $serializer = new CallbackFieldSerializer(
            function (
                string $path,
                Request $request,
                $object,
                SerializerInterface $serializer = null
            ) use ($expectedPath, $expectedRequest, $expectedObject, $expectedSerializer, $expectedValue) {
                self::assertSame($expectedPath, $path);
                self::assertSame($expectedRequest, $request);
                self::assertSame($expectedObject, $object);
                self::assertSame($serializer, $expectedSerializer);

                return $expectedValue;
            }
        );

        self::assertSame(
            $expectedValue,
            $serializer->serializeField($expectedPath, $expectedRequest, $expectedObject, $expectedSerializer)
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
     * @return SerializerInterface
     */
    private function getSerializer(): SerializerInterface
    {
        return $this->getMockBuilder(SerializerInterface::class)->getMockForAbstractClass();
    }
}
