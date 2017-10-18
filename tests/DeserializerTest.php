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
        $serializer = new Serializer($this->getEncoder(), $this->getNormalizer());

        $object = new \stdClass();

        $serializer->serialize(
            $object,
            '{"name": "Name"}',
            'application/json',
            $this->getNormalizerContext()
        );

        self::assertSame('Name', $object->name);
    }

    /**
     * @return EncoderInterface
     */
    private function getEncoder(): EncoderInterface
    {
        /** @var EncoderInterface|\PHPUnit_Framework_MockObject_MockObject $encoder */
        $encoder = $this->getMockBuilder(EncoderInterface::class)->getMockForAbstractClass();

        $encoder->expects(self::any())->method('encode')->willReturnCallback(
            function (string $data, string $contentType) {
                self::assertSame('{"name": "Name"}', $data);
                self::assertSame('application/json', $contentType);

                return json_encode($data, true);
            }
        );

        return $encoder;
    }

    /**
     * @return NormalizerInterface
     */
    private function getNormalizer(): NormalizerInterface
    {
        /** @var NormalizerInterface|\PHPUnit_Framework_MockObject_MockObject $encoder */
        $encoder = $this->getMockBuilder(NormalizerInterface::class)->getMockForAbstractClass();

        $encoder->expects(self::any())->method('normalize')->willReturnCallback(
            function ($object, array $data, NormalizerContextInterface $context = null, string $path = '') {
                self::assertSame(['name' => 'Name'], $data);
                self::assertNotNull($context);
                self::assertSame('', $path);

                $object->name = $data['name'];

                return $object;
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
}
