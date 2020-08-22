<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization\Unit\Container;

use Chubbyphp\Mock\Call;
use Chubbyphp\Mock\MockByCallsTrait;
use Chubbyphp\Serialization\Container\SerializerFactory;
use Chubbyphp\Serialization\Encoder\EncoderInterface;
use Chubbyphp\Serialization\Normalizer\NormalizerInterface;
use Chubbyphp\Serialization\SerializerInterface;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

/**
 * @covers \Chubbyphp\Serialization\Container\SerializerFactory
 *
 * @internal
 */
final class SerializerFactoryTest extends TestCase
{
    use MockByCallsTrait;

    public function testInvoke(): void
    {
        /** @var NormalizerInterface $normalizer */
        $normalizer = $this->getMockByCalls(NormalizerInterface::class);

        /** @var EncoderInterface $encoder */
        $encoder = $this->getMockByCalls(EncoderInterface::class);

        /** @var ContainerInterface $container */
        $container = $this->getMockByCalls(ContainerInterface::class, [
            Call::create('get')->with(NormalizerInterface::class)->willReturn($normalizer),
            Call::create('get')->with(EncoderInterface::class)->willReturn($encoder),
        ]);

        $factory = new SerializerFactory();

        $negotiator = $factory($container);

        self::assertInstanceOf(SerializerInterface::class, $negotiator);
    }
}
