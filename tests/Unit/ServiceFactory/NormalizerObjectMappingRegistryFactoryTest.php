<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization\Unit\ServiceFactory;

use Chubbyphp\Mock\MockMethod\WithReturn;
use Chubbyphp\Mock\MockObjectBuilder;
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
    public function testInvoke(): void
    {
        $builder = new MockObjectBuilder();

        /** @var ContainerInterface $container */
        $container = $builder->create(ContainerInterface::class, [
            new WithReturn('get', [NormalizationObjectMappingInterface::class.'[]'], []),
        ]);

        $factory = new NormalizerObjectMappingRegistryFactory();

        $service = $factory($container);

        self::assertInstanceOf(NormalizerObjectMappingRegistryInterface::class, $service);
    }

    public function testCallStatic(): void
    {
        $builder = new MockObjectBuilder();

        /** @var ContainerInterface $container */
        $container = $builder->create(ContainerInterface::class, [
            new WithReturn('get', [NormalizationObjectMappingInterface::class.'[]default'], []),
        ]);

        $factory = [NormalizerObjectMappingRegistryFactory::class, 'default'];

        $service = $factory($container);

        self::assertInstanceOf(NormalizerObjectMappingRegistryInterface::class, $service);
    }
}
