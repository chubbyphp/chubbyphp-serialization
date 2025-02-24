<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization\Unit\Normalizer;

use Chubbyphp\Mock\MockMethod\WithReturn;
use Chubbyphp\Mock\MockObjectBuilder;
use Chubbyphp\Serialization\Accessor\AccessorInterface;
use Chubbyphp\Serialization\Normalizer\NormalizerContextInterface;
use Chubbyphp\Serialization\Normalizer\Relation\ReferenceOneFieldNormalizer;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Chubbyphp\Serialization\Normalizer\Relation\ReferenceOneFieldNormalizer
 *
 * @internal
 */
final class ReferenceOneFieldNormalizerTest extends TestCase
{
    public function testNormalizeFieldWithNull(): void
    {
        $object = new \stdClass();

        $builder = new MockObjectBuilder();

        /** @var AccessorInterface|MockObject $identifierAccessor */
        $identifierAccessor = $builder->create(AccessorInterface::class, []);

        /** @var AccessorInterface|MockObject $accessor */
        $accessor = $builder->create(AccessorInterface::class, [
            new WithReturn('getValue', [$object], null),
        ]);

        /** @var MockObject|NormalizerContextInterface $context */
        $context = $builder->create(NormalizerContextInterface::class, []);

        $fieldNormalizer = new ReferenceOneFieldNormalizer($identifierAccessor, $accessor);

        $data = $fieldNormalizer->normalizeField('relation', $object, $context);

        self::assertNull($data);
    }

    public function testNormalizeFieldWithObject(): void
    {
        $relation = new \stdClass();

        $object = new \stdClass();

        $builder = new MockObjectBuilder();

        /** @var AccessorInterface|MockObject $identifierAccessor */
        $identifierAccessor = $builder->create(AccessorInterface::class, [
            new WithReturn('getValue', [$relation], 'id1'),
        ]);

        /** @var AccessorInterface|MockObject $accessor */
        $accessor = $builder->create(AccessorInterface::class, [
            new WithReturn('getValue', [$object], $relation),
        ]);

        /** @var MockObject|NormalizerContextInterface $context */
        $context = $builder->create(NormalizerContextInterface::class, []);

        $fieldNormalizer = new ReferenceOneFieldNormalizer($identifierAccessor, $accessor);

        $data = $fieldNormalizer->normalizeField('relation', $object, $context);

        self::assertSame('id1', $data);
    }
}
