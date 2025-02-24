<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization\Unit\Normalizer;

use Chubbyphp\Mock\MockMethod\WithReturn;
use Chubbyphp\Mock\MockObjectBuilder;
use Chubbyphp\Serialization\Accessor\AccessorInterface;
use Chubbyphp\Serialization\Normalizer\DateTimeFieldNormalizer;
use Chubbyphp\Serialization\Normalizer\NormalizerContextInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Chubbyphp\Serialization\Normalizer\DateTimeFieldNormalizer
 *
 * @internal
 */
final class DateTimeFieldNormalizerTest extends TestCase
{
    public function testNormalizeField(): void
    {
        $object = $this->getObject();
        $object->setDate(new \DateTimeImmutable('2017-01-01 22:00:00+01:00'));

        $builder = new MockObjectBuilder();

        /** @var MockObject|NormalizerContextInterface $context */
        $context = $builder->create(NormalizerContextInterface::class, []);

        /** @var AccessorInterface|MockObject $accessor */
        $accessor = $builder->create(AccessorInterface::class, [
            new WithReturn('getValue', [$object], '2017-01-01 22:00:00+01:00'),
        ]);

        $dateTimeFieldNormalizer = new DateTimeFieldNormalizer($accessor);

        self::assertSame(
            '2017-01-01T22:00:00+01:00',
            $dateTimeFieldNormalizer->normalizeField('date', $object, $context)
        );
    }

    public function testNormalizeWithValidDateString(): void
    {
        $object = $this->getObject();
        $object->setDate('2017-01-01 22:00:00+01:00');

        $builder = new MockObjectBuilder();

        /** @var MockObject|NormalizerContextInterface $context */
        $context = $builder->create(NormalizerContextInterface::class, []);

        /** @var AccessorInterface|MockObject $accessor */
        $accessor = $builder->create(AccessorInterface::class, [
            new WithReturn('getValue', [$object], '2017-01-01 22:00:00+01:00'),
        ]);

        $dateTimeFieldNormalizer = new DateTimeFieldNormalizer($accessor);

        self::assertSame(
            '2017-01-01T22:00:00+01:00',
            $dateTimeFieldNormalizer->normalizeField('date', $object, $context)
        );
    }

    public function testNormalizeWithInvalidDateString(): void
    {
        $object = $this->getObject();
        $object->setDate('2017-01-01 25:00:00');

        $builder = new MockObjectBuilder();

        /** @var MockObject|NormalizerContextInterface $context */
        $context = $builder->create(NormalizerContextInterface::class, []);

        /** @var AccessorInterface|MockObject $accessor */
        $accessor = $builder->create(AccessorInterface::class, [
            new WithReturn('getValue', [$object], '2017-01-01 25:00:00'),
        ]);

        $dateTimeFieldNormalizer = new DateTimeFieldNormalizer($accessor);

        self::assertSame(
            '2017-01-01 25:00:00',
            $dateTimeFieldNormalizer->normalizeField('date', $object, $context)
        );
    }

    public function testNormalizeWithNull(): void
    {
        $object = $this->getObject();

        $builder = new MockObjectBuilder();

        /** @var MockObject|NormalizerContextInterface $context */
        $context = $builder->create(NormalizerContextInterface::class, []);

        /** @var AccessorInterface|MockObject $accessor */
        $accessor = $builder->create(AccessorInterface::class, [
            new WithReturn('getValue', [$object], null),
        ]);

        $dateTimeFieldNormalizer = new DateTimeFieldNormalizer($accessor);

        self::assertNull(
            $dateTimeFieldNormalizer->normalizeField('date', $object, $context)
        );
    }

    private function getObject()
    {
        return new class {
            private null|\DateTimeImmutable|string $date = null;

            /**
             * @return null|\DateTime|string
             */
            public function getDate()
            {
                return $this->date;
            }

            public function setDate(null|\DateTimeImmutable|string $date): self
            {
                $this->date = $date;

                return $this;
            }
        };
    }
}
