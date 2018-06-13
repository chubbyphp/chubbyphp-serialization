<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization\Encoder;

use Chubbyphp\Serialization\Encoder\Encoder;
use Chubbyphp\Serialization\Encoder\TypeEncoderInterface;
use Chubbyphp\Serialization\SerializerLogicException;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Chubbyphp\Serialization\Encoder\Encoder
 */
class EncoderTest extends TestCase
{
    public function testGetContentTypes()
    {
        $encoder = new Encoder([$this->getTypeEncoder()]);

        self::assertSame(['application/json'], $encoder->getContentTypes());
    }

    public function testEncode()
    {
        $encoder = new Encoder([$this->getTypeEncoder()]);

        self::assertSame('{"key":"value"}', $encoder->encode(['key' => 'value'], 'application/json'));
    }

    public function testDecodeWithMissingType()
    {
        $this->expectException(SerializerLogicException::class);
        $this->expectExceptionMessage('There is no encoder for content-type: "application/xml"');

        $encoder = new Encoder([$this->getTypeEncoder()]);

        $encoder->encode(['key' => 'value'], 'application/xml');
    }

    /**
     * @return TypeEncoderInterface
     */
    private function getTypeEncoder(): TypeEncoderInterface
    {
        /** @var TypeEncoderInterface|\PHPUnit_Framework_MockObject_MockObject $encoderType */
        $encoderType = $this->getMockBuilder(TypeEncoderInterface::class)
            ->setMethods(['getContentType', 'encode'])
            ->getMockForAbstractClass();

        $encoderType->expects(self::any())->method('getContentType')->willReturn('application/json');
        $encoderType->expects(self::any())->method('encode')->willReturnCallback(function (array $data) {
            return json_encode($data);
        });

        return $encoderType;
    }
}
