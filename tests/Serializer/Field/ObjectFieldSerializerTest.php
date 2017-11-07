<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization\Serializer\Field;

use Chubbyphp\Serialization\Accessor\AccessorInterface;
use Chubbyphp\Serialization\Serializer\Field\ObjectFieldSerializer;
use Chubbyphp\Serialization\SerializerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * @covers \Chubbyphp\Serialization\Serializer\Field\ObjectFieldSerializer
 */
class ObjectFieldSerializerTest extends \PHPUnit_Framework_TestCase
{
    public function testSerializeField()
    {
        $expectedPath = 'path';
        $expectedRequest = $this->getRequest();
        $expectedObject = new \stdClass();
        $expectedSerializer = $this->getSerializer();
        $expectedValue = [];

        $serializer = new ObjectFieldSerializer($this->getAccessor(new \stdClass()));

        self::assertSame(
            $expectedValue,
            $serializer->serializeField($expectedPath, $expectedRequest, $expectedObject, $expectedSerializer)
        );
    }

    public function testSerializeFieldWithNull()
    {
        $expectedPath = 'path';
        $expectedRequest = $this->getRequest();
        $expectedObject = new \stdClass();
        $expectedSerializer = $this->getSerializer();
        $serializer = new ObjectFieldSerializer($this->getAccessor(null));

        self::assertNull(
            $serializer->serializeField($expectedPath, $expectedRequest, $expectedObject, $expectedSerializer)
        );
    }

    public function testSerializeFieldWithoutSerializer()
    {
        self::expectException(\RuntimeException::class);
        self::expectExceptionMessage('Serializer needed: Chubbyphp\Serialization\SerializerInterface');

        $expectedPath = 'path';
        $expectedRequest = $this->getRequest();
        $expectedObject = new \stdClass();

        $serializer = new ObjectFieldSerializer($this->getAccessor(new \stdClass()));

        $serializer->serializeField($expectedPath, $expectedRequest, $expectedObject);
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
        /** @var SerializerInterface|\PHPUnit_Framework_MockObject_MockObject $serializer */
        $serializer = $this
            ->getMockBuilder(SerializerInterface::class)
            ->setMethods(['serialize'])
            ->getMockForAbstractClass();

        $serializer->expects(self::any())->method('serialize')->willReturnCallback(
            function (Request $request, $object, string $path = '') {
                return (array) $object;
            }
        );

        return $serializer;
    }

    /**
     * @param mixed $value
     *
     * @return AccessorInterface
     */
    private function getAccessor($value): AccessorInterface
    {
        /** @var AccessorInterface|\PHPUnit_Framework_MockObject_MockObject $accessor */
        $accessor = $this->getMockBuilder(AccessorInterface::class)->setMethods(['getValue'])->getMockForAbstractClass();

        $accessor->expects(self::any())->method('getValue')->willReturn($value);

        return $accessor;
    }
}
