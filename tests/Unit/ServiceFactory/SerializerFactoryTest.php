<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization\Unit\ServiceFactory;

use Chubbyphp\Mock\Call;
use Chubbyphp\Mock\MockByCallsTrait;
use Chubbyphp\Serialization\Encoder\EncoderInterface;
use Chubbyphp\Serialization\Normalizer\NormalizerInterface;
use Chubbyphp\Serialization\SerializerInterface;
use Chubbyphp\Serialization\ServiceFactory\SerializerFactory;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

/**
 * @covers \Chubbyphp\Serialization\ServiceFactory\SerializerFactory
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
            Call::create('has')->with(NormalizerInterface::class)->willReturn(true),
            Call::create('get')->with(NormalizerInterface::class)->willReturn($normalizer),
            Call::create('has')->with(EncoderInterface::class)->willReturn(true),
            Call::create('get')->with(EncoderInterface::class)->willReturn($encoder),
        ]);

        $factory = new SerializerFactory();

        $service = $factory($container);

        self::assertInstanceOf(SerializerInterface::class, $service);
    }

    public function testCallStatic(): void
    {
        /** @var NormalizerInterface $normalizer */
        $normalizer = $this->getMockByCalls(NormalizerInterface::class);

        /** @var EncoderInterface $encoder */
        $encoder = $this->getMockByCalls(EncoderInterface::class);

        /** @var ContainerInterface $container */
        $container = $this->getMockByCalls(ContainerInterface::class, [
            Call::create('has')->with(NormalizerInterface::class.'default')->willReturn(true),
            Call::create('get')->with(NormalizerInterface::class.'default')->willReturn($normalizer),
            Call::create('has')->with(EncoderInterface::class.'default')->willReturn(true),
            Call::create('get')->with(EncoderInterface::class.'default')->willReturn($encoder),
        ]);

        $factory = [SerializerFactory::class, 'default'];

        $service = $factory($container);

        self::assertInstanceOf(SerializerInterface::class, $service);
    }
}
