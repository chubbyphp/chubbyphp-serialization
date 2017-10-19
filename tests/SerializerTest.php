<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization;

use Chubbyphp\Serialization\Encoder\EncoderInterface;
use Chubbyphp\Serialization\Normalizer\NormalizerContextInterface;
use Chubbyphp\Serialization\Normalizer\NormalizerInterface;
use Chubbyphp\Serialization\Serializer;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Chubbyphp\Serialization\Serializer
 */
class SerializerTest extends TestCase
{
    public function testSerialize()
    {
        $serializer = new Serializer($this->getNormalizer(), $this->getEncoder());

        $object = new \stdClass();
        $object->name = 'Name';

        $data = $serializer->serialize(
            $object,
            'application/json',
            $this->getNormalizerContext()
        );

        self::assertSame(json_encode(['name' => 'Name']), $data);
    }

    public function testNormalize()
    {
        $serializer = new Serializer($this->getNormalizer(), $this->getEncoder());

        $object = new \stdClass();
        $object->name = 'Name';

        $data = $serializer->normalize(
            $object,
            $this->getNormalizerContext()
        );

        self::assertSame(['name' => 'Name'], $data);
    }

    public function testGetContentTypes()
    {
        $serializer = new Serializer($this->getNormalizer(), $this->getEncoder());

        self::assertSame(['application/json'], $serializer->getContentTypes());
    }

    public function testEncode()
    {
        $serializer = new Serializer($this->getNormalizer(), $this->getEncoder());

        $json = $serializer->encode(
            ['name' => 'Name'],
            'application/json'
        );

        self::assertSame(json_encode(['name' => 'Name']), $json);
    }

    /**
     * @return NormalizerInterface
     */
    private function getNormalizer(): NormalizerInterface
    {
        /** @var NormalizerInterface|\PHPUnit_Framework_MockObject_MockObject $encoder */
        $encoder = $this->getMockBuilder(NormalizerInterface::class)->getMockForAbstractClass();

        $encoder->expects(self::any())->method('normalize')->willReturnCallback(
            function ($object, NormalizerContextInterface $context = null, string $path = '') {
                self::assertNotNull($context);
                self::assertSame('', $path);

                return [
                    'name' => $object->name,
                ];
            }
        );

        return $encoder;
    }

    /**
     * @return NormalizerContextInterface
     */
    private function getNormalizerContext(): NormalizerContextInterface
    {
        /** @var NormalizerContextInterface|\PHPUnit_Framework_MockObject_MockObject $context */
        $context = $this->getMockBuilder(NormalizerContextInterface::class)->getMockForAbstractClass();

        return $context;
    }

    /**
     * @return EncoderInterface
     */
    private function getEncoder(): EncoderInterface
    {
        /** @var EncoderInterface|\PHPUnit_Framework_MockObject_MockObject $encoder */
        $encoder = $this->getMockBuilder(EncoderInterface::class)->getMockForAbstractClass();

        $encoder->expects(self::any())->method('getContentTypes')->willReturn(['application/json']);

        $encoder->expects(self::any())->method('encode')->willReturnCallback(
            function (array $data, string $contentType) {
                self::assertSame(['name' => 'Name'], $data);
                self::assertSame('application/json', $contentType);

                return json_encode($data);
            }
        );

        return $encoder;
    }
}
