<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization\Unit\Container;

use Chubbyphp\Mock\Call;
use Chubbyphp\Mock\MockByCallsTrait;
use Chubbyphp\Serialization\Container\NormalizerObjectMappingRegistryFactory;
use Chubbyphp\Serialization\Mapping\NormalizationObjectMappingInterface;
use Chubbyphp\Serialization\Normalizer\NormalizerObjectMappingRegistryInterface;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

/**
 * @covers \Chubbyphp\Serialization\Container\NormalizerObjectMappingRegistryFactory
 *
 * @internal
 */
final class NormalizerObjectMappingRegistryFactoryTest extends TestCase
{
    use MockByCallsTrait;

    public function testInvoke(): void
    {
        /** @var NormalizationObjectMappingInterface $normalizationObjectMapping */
        $normalizationObjectMapping = $this->getMockByCalls(NormalizationObjectMappingInterface::class, [
            Call::create('getClass')->with()->willReturn('class'),
        ]);

        /** @var ContainerInterface $container */
        $container = $this->getMockByCalls(ContainerInterface::class, [
            Call::create('get')
                ->with(NormalizationObjectMappingInterface::class.'[]')
                ->willReturn([$normalizationObjectMapping]),
        ]);

        $factory = new NormalizerObjectMappingRegistryFactory();

        $negotiator = $factory($container);

        self::assertInstanceOf(NormalizerObjectMappingRegistryInterface::class, $negotiator);
    }
}
