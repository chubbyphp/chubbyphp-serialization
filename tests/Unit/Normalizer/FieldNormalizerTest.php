<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization\Unit\Normalizer;

use Chubbyphp\Mock\MockMethod\WithReturn;
use Chubbyphp\Mock\MockObjectBuilder;
use Chubbyphp\Serialization\Accessor\AccessorInterface;
use Chubbyphp\Serialization\Normalizer\FieldNormalizer;
use Chubbyphp\Serialization\Normalizer\NormalizerContextInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Chubbyphp\Serialization\Normalizer\FieldNormalizer
 *
 * @internal
 */
final class FieldNormalizerTest extends TestCase
{
    public function testNormalizeField(): void
    {
        $object = new \stdClass();

        $builder = new MockObjectBuilder();

        /** @var MockObject|NormalizerContextInterface $context */
        $context = $builder->create(NormalizerContextInterface::class, []);

        /** @var AccessorInterface|MockObject $accessor */
        $accessor = $builder->create(AccessorInterface::class, [
            new WithReturn('getValue', [$object], 'name'),
        ]);

        $fieldNormalizer = new FieldNormalizer($accessor);

        self::assertSame(
            'name',
            $fieldNormalizer->normalizeField('name', $object, $context)
        );
    }
}
