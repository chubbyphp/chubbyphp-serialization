<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization;

use Chubbyphp\Mock\Call;
use Chubbyphp\Mock\MockByCallsTrait;
use Chubbyphp\Serialization\Encoder\EncoderInterface;
use Chubbyphp\Serialization\Normalizer\NormalizerContextInterface;
use Chubbyphp\Serialization\Normalizer\NormalizerInterface;
use Chubbyphp\Serialization\Serializer;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Chubbyphp\Serialization\Serializer
 */
class SerializerTest extends TestCase
{
    use MockByCallsTrait;

    public function testSerialize()
    {
        $object = new \stdClass();
        $object->name = 'Name';

        /** @var NormalizerContextInterface|MockObject $context */
        $context = $this->getMockByCalls(NormalizerContextInterface::class);

        /** @var NormalizerInterface|MockObject $normalizer */
        $normalizer = $this->getMockByCalls(NormalizerInterface::class, [
            Call::create('normalize')->with($object, $context, 'path')->willReturn(['name' => 'Name']),
        ]);

        /** @var EncoderInterface|MockObject $encoder */
        $encoder = $this->getMockByCalls(EncoderInterface::class, [
            Call::create('encode')->with(['name' => 'Name'], 'application/json')->willReturn('{"name":"Name"}'),
        ]);

        $serializer = new Serializer($normalizer, $encoder);

        $data = $serializer->serialize($object, 'application/json', $context, 'path');

        self::assertSame('{"name":"Name"}', $data);
    }

    public function testNormalize()
    {
        $object = new \stdClass();
        $object->name = 'Name';

        /** @var NormalizerContextInterface|MockObject $context */
        $context = $this->getMockByCalls(NormalizerContextInterface::class);

        /** @var NormalizerInterface|MockObject $normalizer */
        $normalizer = $this->getMockByCalls(NormalizerInterface::class, [
            Call::create('normalize')->with($object, $context, 'path')->willReturn(['name' => 'Name']),
        ]);

        /** @var EncoderInterface|MockObject $encoder */
        $encoder = $this->getMockByCalls(EncoderInterface::class);

        $serializer = new Serializer($normalizer, $encoder);

        $data = $serializer->normalize($object, $context, 'path');

        self::assertSame(['name' => 'Name'], $data);
    }

    public function testGetContentTypes()
    {
        /** @var NormalizerInterface|MockObject $normalizer */
        $normalizer = $this->getMockByCalls(NormalizerInterface::class);

        /** @var EncoderInterface|MockObject $encoder */
        $encoder = $this->getMockByCalls(EncoderInterface::class, [
            Call::create('getContentTypes')->with()->willReturn(['application/json']),
        ]);

        $serializer = new Serializer($normalizer, $encoder);

        self::assertSame(['application/json'], $serializer->getContentTypes());
    }

    public function testEncode()
    {
        /** @var NormalizerInterface|MockObject $normalizer */
        $normalizer = $this->getMockByCalls(NormalizerInterface::class);

        /** @var EncoderInterface|MockObject $encoder */
        $encoder = $this->getMockByCalls(EncoderInterface::class, [
            Call::create('encode')->with(['name' => 'Name'], 'application/json')->willReturn('{"name":"Name"}'),
        ]);

        $serializer = new Serializer($normalizer, $encoder);

        self::assertSame('{"name":"Name"}', $serializer->encode(['name' => 'Name'], 'application/json'));
    }
}
