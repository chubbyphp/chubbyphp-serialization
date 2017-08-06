<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization\Serializer\Field;

use Chubbyphp\Serialization\Accessor\AccessorInterface;
use Chubbyphp\Serialization\Serializer\Field\ValueFieldSerializer;
use Chubbyphp\Serialization\SerializerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;

class ValueFieldSerializerTest extends \PHPUnit_Framework_TestCase
{
    public function testSerializeBoolean()
    {
        $expectedPath = 'path';
        $expectedRequest = $this->getRequest();
        $expectedObject = new \stdClass();
        $expectedSerializer = $this->getSerializer();
        $expectedValue = true;

        $serializer = new ValueFieldSerializer($this->getAccessor((string) $expectedValue), ValueFieldSerializer::CAST_BOOL);

        self::assertSame(
            $expectedValue,
            $serializer->serializeField($expectedPath, $expectedRequest, $expectedObject, $expectedSerializer)
        );
    }

    public function testSerializeWithFloat()
    {
        $expectedPath = 'path';
        $expectedRequest = $this->getRequest();
        $expectedObject = new \stdClass();
        $expectedSerializer = $this->getSerializer();
        $expectedValue = 1.3;

        $serializer = new ValueFieldSerializer($this->getAccessor((string) $expectedValue), ValueFieldSerializer::CAST_FLOAT);

        self::assertSame(
            $expectedValue,
            $serializer->serializeField($expectedPath, $expectedRequest, $expectedObject, $expectedSerializer)
        );
    }

    public function testSerializeWithInteger()
    {
        $expectedPath = 'path';
        $expectedRequest = $this->getRequest();
        $expectedObject = new \stdClass();
        $expectedSerializer = $this->getSerializer();
        $expectedValue = 1;

        $serializer = new ValueFieldSerializer($this->getAccessor((string) $expectedValue), ValueFieldSerializer::CAST_INT);

        self::assertSame(
            $expectedValue,
            $serializer->serializeField($expectedPath, $expectedRequest, $expectedObject, $expectedSerializer)
        );
    }

    public function testSerializeWithString()
    {
        $expectedPath = 'path';
        $expectedRequest = $this->getRequest();
        $expectedObject = new \stdClass();
        $expectedSerializer = $this->getSerializer();
        $expectedValue = 'value';

        $serializer = new ValueFieldSerializer($this->getAccessor($expectedValue));

        self::assertSame(
            $expectedValue,
            $serializer->serializeField($expectedPath, $expectedRequest, $expectedObject, $expectedSerializer)
        );
    }

    public function testSerializeWithArray()
    {
        $expectedPath = 'path';
        $expectedRequest = $this->getRequest();
        $expectedObject = new \stdClass();
        $expectedSerializer = $this->getSerializer();
        $expectedValue = ['key' => 'value'];

        $serializer = new ValueFieldSerializer($this->getAccessor($expectedValue));

        self::assertSame(
            $expectedValue,
            $serializer->serializeField($expectedPath, $expectedRequest, $expectedObject, $expectedSerializer)
        );
    }

    public function testSerializeWithInvalidCast()
    {
        self::expectException(\InvalidArgumentException::class);
        self::expectExceptionMessage('Cast test is not support, supported casts: bool, float, int');

        $expectedPath = 'path';
        $expectedRequest = $this->getRequest();
        $expectedObject = new \stdClass();
        $expectedSerializer = $this->getSerializer();

        $serializer = new ValueFieldSerializer($this->getAccessor('value'), 'test');

        $serializer->serializeField($expectedPath, $expectedRequest, $expectedObject, $expectedSerializer);
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
