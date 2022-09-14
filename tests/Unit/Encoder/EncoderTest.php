<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization\Unit\Encoder;

use Chubbyphp\DecodeEncode\Encoder\Encoder as BaseEncoder;
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
final class EncoderTest extends TestCase
{
    use MockByCallsTrait;

    public function testGetContentTypes(): void
    {
        /** @var MockObject|TypeEncoderInterface $typeEncoder */
        $typeEncoder = $this->getMockByCalls(TypeEncoderInterface::class, [
            Call::create('getContentType')->with()->willReturn('application/json'),
        ]);

        $encoder = new Encoder([$typeEncoder]);

        error_clear_last();

        self::assertSame(['application/json'], $encoder->getContentTypes());

        $error = error_get_last();

        self::assertNotNull($error);

        self::assertSame(E_USER_DEPRECATED, $error['type']);
        self::assertSame(sprintf(
            '%s:getContentTypes use %s:getContentTypes',
            Encoder::class,
            BaseEncoder::class
        ), $error['message']);
    }

    public function testEncode(): void
    {
        /** @var MockObject|TypeEncoderInterface $typeEncoder */
        $typeEncoder = $this->getMockByCalls(TypeEncoderInterface::class, [
            Call::create('getContentType')->with()->willReturn('application/json'),
            Call::create('encode')->with(['key' => 'value'])->willReturn('{"key":"value"}'),
        ]);

        $encoder = new Encoder([$typeEncoder]);

        error_clear_last();

        self::assertSame('{"key":"value"}', $encoder->encode(['key' => 'value'], 'application/json'));

        $error = error_get_last();

        self::assertNotNull($error);

        self::assertSame(E_USER_DEPRECATED, $error['type']);
        self::assertSame(sprintf(
            '%s:encode use %s:encode',
            Encoder::class,
            BaseEncoder::class
        ), $error['message']);
    }

    public function testDecodeWithMissingType(): void
    {
        $this->expectException(SerializerLogicException::class);
        $this->expectExceptionMessage('There is no decoder/encoder for content-type: "application/xml"');

        /** @var MockObject|TypeEncoderInterface $typeEncoder */
        $typeEncoder = $this->getMockByCalls(TypeEncoderInterface::class, [
            Call::create('getContentType')->with()->willReturn('application/json'),
        ]);

        $encoder = new Encoder([$typeEncoder]);

        $encoder->encode(['key' => 'value'], 'application/xml');
    }
}
