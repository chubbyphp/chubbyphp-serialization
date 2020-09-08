<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization\Unit\ServiceFactory;

use Chubbyphp\Mock\Call;
use Chubbyphp\Mock\MockByCallsTrait;
use Chubbyphp\Serialization\Normalizer\NormalizerInterface;
use Chubbyphp\Serialization\Normalizer\NormalizerObjectMappingRegistryInterface;
use Chubbyphp\Serialization\ServiceFactory\NormalizerFactory;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

/**
 * @covers \Chubbyphp\Serialization\ServiceFactory\NormalizerFactory
 *
 * @internal
 */
final class NormalizerFactoryTest extends TestCase
{
    use MockByCallsTrait;

    public function testInvoke(): void
    {
        /** @var NormalizerObjectMappingRegistryInterface $normalizerObjectMappingRegistry */
        $normalizerObjectMappingRegistry = $this->getMockByCalls(NormalizerObjectMappingRegistryInterface::class);

        /** @var ContainerInterface $container */
        $container = $this->getMockByCalls(ContainerInterface::class, [
            Call::create('has')->with(NormalizerObjectMappingRegistryInterface::class)->willReturn(true),
            Call::create('get')
                ->with(NormalizerObjectMappingRegistryInterface::class)
                ->willReturn($normalizerObjectMappingRegistry),
            Call::create('has')->with(LoggerInterface::class)->willReturn(false),
        ]);

        $factory = new NormalizerFactory();

        $service = $factory($container);

        self::assertInstanceOf(NormalizerInterface::class, $service);
    }

    public function testCallStatic(): void
    {
        /** @var NormalizerObjectMappingRegistryInterface $normalizerObjectMappingRegistry */
        $normalizerObjectMappingRegistry = $this->getMockByCalls(NormalizerObjectMappingRegistryInterface::class);

        /** @var ContainerInterface $container */
        $container = $this->getMockByCalls(ContainerInterface::class, [
            Call::create('has')->with(NormalizerObjectMappingRegistryInterface::class.'default')->willReturn(true),
            Call::create('get')
                ->with(NormalizerObjectMappingRegistryInterface::class.'default')
                ->willReturn($normalizerObjectMappingRegistry),
            Call::create('has')->with(LoggerInterface::class)->willReturn(false),
        ]);

        $factory = [NormalizerFactory::class, 'default'];

        $service = $factory($container);

        self::assertInstanceOf(NormalizerInterface::class, $service);
    }
}
