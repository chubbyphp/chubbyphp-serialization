<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization\Unit\Container;

use Chubbyphp\Mock\Call;
use Chubbyphp\Mock\MockByCallsTrait;
use Chubbyphp\Serialization\Container\NormalizerFactory;
use Chubbyphp\Serialization\Normalizer\NormalizerInterface;
use Chubbyphp\Serialization\Normalizer\NormalizerObjectMappingRegistryInterface;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

/**
 * @covers \Chubbyphp\Serialization\Container\NormalizerFactory
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

        /** @var LoggerInterface $logger */
        $logger = $this->getMockByCalls(LoggerInterface::class);

        /** @var ContainerInterface $container */
        $container = $this->getMockByCalls(ContainerInterface::class, [
            Call::create('get')
                ->with(NormalizerObjectMappingRegistryInterface::class)
                ->willReturn($normalizerObjectMappingRegistry),
            Call::create('get')->with(LoggerInterface::class)->willReturn($logger),
        ]);

        $factory = new NormalizerFactory();

        $negotiator = $factory($container);

        self::assertInstanceOf(NormalizerInterface::class, $negotiator);
    }
}
