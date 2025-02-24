<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization\Unit\Mapping;

use Chubbyphp\Mock\MockMethod\WithReturn;
use Chubbyphp\Mock\MockObjectBuilder;
use Chubbyphp\Serialization\Mapping\LazyNormalizationObjectMapping;
use Chubbyphp\Serialization\Mapping\NormalizationFieldMappingInterface;
use Chubbyphp\Serialization\Mapping\NormalizationLinkMappingInterface;
use Chubbyphp\Serialization\Mapping\NormalizationObjectMappingInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

/**
 * @covers \Chubbyphp\Serialization\Mapping\LazyNormalizationObjectMapping
 *
 * @internal
 */
final class LazyNormalizationObjectMappingTest extends TestCase
{
    public function testInvoke(): void
    {
        $builder = new MockObjectBuilder();

        $normalizationFieldMappings = [
            $builder->create(NormalizationFieldMappingInterface::class, []),
        ];

        $normalizationEmbeddedFieldMappings = [
            $builder->create(NormalizationFieldMappingInterface::class, []),
        ];

        $normalizationLinkMappings = [
            $builder->create(NormalizationLinkMappingInterface::class, []),
        ];

        /** @var MockObject|NormalizationObjectMappingInterface $normalizationObjectMapping */
        $normalizationObjectMapping = $builder->create(NormalizationObjectMappingInterface::class, [
            new WithReturn('getNormalizationType', [], 'type'),
            new WithReturn('getNormalizationFieldMappings', ['path'], $normalizationFieldMappings),
            new WithReturn('getNormalizationEmbeddedFieldMappings', ['path'], $normalizationEmbeddedFieldMappings),
            new WithReturn('getNormalizationLinkMappings', ['path'], $normalizationLinkMappings),
        ]);

        /** @var ContainerInterface|MockObject $container */
        $container = $builder->create(ContainerInterface::class, [
            new WithReturn('get', ['service'], $normalizationObjectMapping),
            new WithReturn('get', ['service'], $normalizationObjectMapping),
            new WithReturn('get', ['service'], $normalizationObjectMapping),
            new WithReturn('get', ['service'], $normalizationObjectMapping),
        ]);

        $objectMapping = new LazyNormalizationObjectMapping($container, 'service', \stdClass::class);

        self::assertEquals(\stdClass::class, $objectMapping->getClass());
        self::assertSame('type', $objectMapping->getNormalizationType());
        self::assertSame($normalizationFieldMappings, $objectMapping->getNormalizationFieldMappings('path'));
        self::assertSame(
            $normalizationEmbeddedFieldMappings,
            $objectMapping->getNormalizationEmbeddedFieldMappings('path')
        );
        self::assertSame($normalizationLinkMappings, $objectMapping->getNormalizationLinkMappings('path'));
    }
}
