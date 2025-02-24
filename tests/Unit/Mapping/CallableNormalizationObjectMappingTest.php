<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization\Unit\Mapping;

use Chubbyphp\Mock\MockMethod\WithReturn;
use Chubbyphp\Mock\MockObjectBuilder;
use Chubbyphp\Serialization\Mapping\CallableNormalizationObjectMapping;
use Chubbyphp\Serialization\Mapping\NormalizationFieldMappingInterface;
use Chubbyphp\Serialization\Mapping\NormalizationLinkMappingInterface;
use Chubbyphp\Serialization\Mapping\NormalizationObjectMappingInterface;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Chubbyphp\Serialization\Mapping\CallableNormalizationObjectMapping
 *
 * @internal
 */
final class CallableNormalizationObjectMappingTest extends TestCase
{
    public function testGetClass(): void
    {
        $mapping = new CallableNormalizationObjectMapping(\stdClass::class, static function (): void {});

        self::assertSame(\stdClass::class, $mapping->getClass());
    }

    public function testGetNormalizationType(): void
    {
        $builder = new MockObjectBuilder();

        /** @var NormalizationObjectMappingInterface $normalizationObjectMapping */
        $normalizationObjectMapping = $builder->create(NormalizationObjectMappingInterface::class, [
            new WithReturn('getNormalizationType', [], 'type'),
        ]);

        $mapping = new CallableNormalizationObjectMapping(\stdClass::class, static fn () => $normalizationObjectMapping);

        self::assertSame('type', $mapping->getNormalizationType());
    }

    public function testGetNormalizationFieldMappings(): void
    {
        $builder = new MockObjectBuilder();

        /** @var NormalizationFieldMappingInterface $fieldMapping */
        $fieldMapping = $builder->create(NormalizationFieldMappingInterface::class, []);

        $normalizationObjectMapping = $builder->create(NormalizationObjectMappingInterface::class, [
            new WithReturn('getNormalizationFieldMappings', ['path'], [$fieldMapping]),
        ]);

        $mapping = new CallableNormalizationObjectMapping(\stdClass::class, static fn () => $normalizationObjectMapping);

        self::assertSame($fieldMapping, $mapping->getNormalizationFieldMappings('path')[0]);
    }

    public function testGetNormalizationEmbeddedFieldMappings(): void
    {
        $builder = new MockObjectBuilder();

        /** @var NormalizationFieldMappingInterface $fieldMapping */
        $fieldMapping = $builder->create(NormalizationFieldMappingInterface::class, []);

        $normalizationObjectMapping = $builder->create(NormalizationObjectMappingInterface::class, [
            new WithReturn('getNormalizationEmbeddedFieldMappings', ['path'], [$fieldMapping]),
        ]);

        $mapping = new CallableNormalizationObjectMapping(\stdClass::class, static fn () => $normalizationObjectMapping);

        self::assertSame($fieldMapping, $mapping->getNormalizationEmbeddedFieldMappings('path')[0]);
    }

    public function testGetNormalizationLinkMappings(): void
    {
        $builder = new MockObjectBuilder();

        /** @var NormalizationLinkMappingInterface $linkMapping */
        $linkMapping = $builder->create(NormalizationLinkMappingInterface::class, []);

        $normalizationObjectMapping = $builder->create(NormalizationObjectMappingInterface::class, [
            new WithReturn('getNormalizationLinkMappings', ['path'], [$linkMapping]),
        ]);

        $mapping = new CallableNormalizationObjectMapping(\stdClass::class, static fn () => $normalizationObjectMapping);

        self::assertSame($linkMapping, $mapping->getNormalizationLinkMappings('path')[0]);
    }
}
