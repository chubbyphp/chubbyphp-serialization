<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization\Unit\ServiceFactory;

use Chubbyphp\Mock\Call;
use Chubbyphp\Mock\MockByCallsTrait;
use Chubbyphp\Serialization\Mapping\NormalizationObjectMappingInterface;
use Chubbyphp\Serialization\Normalizer\NormalizerObjectMappingRegistryInterface;
use Chubbyphp\Serialization\ServiceFactory\NormalizerObjectMappingRegistryFactory;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

/**
 * @covers \Chubbyphp\Serialization\ServiceFactory\NormalizerObjectMappingRegistryFactory
 *
 * @internal
 */
final class NormalizerObjectMappingRegistryFactoryTest extends TestCase
{
    use MockByCallsTrait;

    public function testInvoke(): void
    {
        /** @var ContainerInterface $container */
        $container = $this->getMockByCalls(ContainerInterface::class, [
            Call::create('get')->with(NormalizationObjectMappingInterface::class.'[]')->willReturn([]),
        ]);

        $factory = new NormalizerObjectMappingRegistryFactory();

        $service = $factory($container);

        self::assertInstanceOf(NormalizerObjectMappingRegistryInterface::class, $service);
    }

    public function testCallStatic(): void
    {
        /** @var ContainerInterface $container */
        $container = $this->getMockByCalls(ContainerInterface::class, [
            Call::create('get')->with(NormalizationObjectMappingInterface::class.'[]default')->willReturn([]),
        ]);

        $factory = [NormalizerObjectMappingRegistryFactory::class, 'default'];

        $service = $factory($container);

        self::assertInstanceOf(NormalizerObjectMappingRegistryInterface::class, $service);
    }
}
