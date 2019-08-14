<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization\Encoder;

use Chubbyphp\Mock\Call;
use Chubbyphp\Mock\MockByCallsTrait;
use Chubbyphp\Serialization\Encoder\Encoder;
use Chubbyphp\Serialization\Encoder\TypeEncoderInterface;
use Chubbyphp\Serialization\SerializerLogicException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Chubbyphp\Serialization\Encoder\Encoder
 *
 * @internal
 */
class EncoderTest extends TestCase
{
    use MockByCallsTrait;

    public function testGetContentTypes()
    {
        /** @var TypeEncoderInterface|MockObject $typeEncoder */
        $typeEncoder = $this->getMockByCalls(TypeEncoderInterface::class, [
            Call::create('getContentType')->with()->willReturn('application/json'),
        ]);

        $encoder = new Encoder([$typeEncoder]);

        self::assertSame(['application/json'], $encoder->getContentTypes());
    }

    public function testEncode()
    {
        /** @var TypeEncoderInterface|MockObject $typeEncoder */
        $typeEncoder = $this->getMockByCalls(TypeEncoderInterface::class, [
            Call::create('getContentType')->with()->willReturn('application/json'),
            Call::create('encode')->with(['key' => 'value'])->willReturn('{"key":"value"}'),
        ]);

        $encoder = new Encoder([$typeEncoder]);

        self::assertSame('{"key":"value"}', $encoder->encode(['key' => 'value'], 'application/json'));
    }

    public function testDecodeWithMissingType()
    {
        $this->expectException(SerializerLogicException::class);
        $this->expectExceptionMessage('There is no encoder for content-type: "application/xml"');

        /** @var TypeEncoderInterface|MockObject $typeEncoder */
        $typeEncoder = $this->getMockByCalls(TypeEncoderInterface::class, [
            Call::create('getContentType')->with()->willReturn('application/json'),
        ]);

        $encoder = new Encoder([$typeEncoder]);

        $encoder->encode(['key' => 'value'], 'application/xml');
    }
}
