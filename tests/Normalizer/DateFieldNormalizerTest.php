<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization\Normalizer;

use Chubbyphp\Mock\Call;
use Chubbyphp\Mock\MockByCallsTrait;
use Chubbyphp\Serialization\Normalizer\DateFieldNormalizer;
use Chubbyphp\Serialization\Normalizer\FieldNormalizerInterface;
use Chubbyphp\Serialization\Normalizer\NormalizerContextInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Chubbyphp\Serialization\Normalizer\DateFieldNormalizer
 *
 * @internal
 */
class DateFieldNormalizerTest extends TestCase
{
    use MockByCallsTrait;

    public function testNormalizeField()
    {
        $object = $this->getObject();
        $object->setDate(new \DateTime('2017-01-01 22:00:00+01:00'));

        /** @var NormalizerContextInterface|MockObject $context */
        $context = $this->getMockByCalls(NormalizerContextInterface::class);

        /** @var FieldNormalizerInterface|MockObject $fieldNormalizer */
        $fieldNormalizer = $this->getMockByCalls(FieldNormalizerInterface::class, [
            Call::create('normalizeField')
                ->with('date', $object, $context, null)
                ->willReturn('2017-01-01T22:00:00+01:00'),
        ]);

        $dateFieldNormalizer = new DateFieldNormalizer($fieldNormalizer);

        self::assertSame(
            '2017-01-01T22:00:00+01:00',
            $dateFieldNormalizer->normalizeField(
                'date',
                $object,
                $context
            )
        );

        $error = error_get_last();

        error_clear_last();

        self::assertEquals(E_USER_DEPRECATED, $error['type']);
        self::assertEquals('Use Chubbyphp\Serialization\Normalizer\DateTimeFieldNormalizer instead', $error['message']);
    }

    public function testNormalizeWithValidDateString()
    {
        $object = $this->getObject();
        $object->setDate('2017-01-01 22:00:00+01:00');

        /** @var NormalizerContextInterface|MockObject $context */
        $context = $this->getMockByCalls(NormalizerContextInterface::class);

        /** @var FieldNormalizerInterface|MockObject $fieldNormalizer */
        $fieldNormalizer = $this->getMockByCalls(FieldNormalizerInterface::class, [
            Call::create('normalizeField')
                ->with('date', $object, $context, null)
                ->willReturn('2017-01-01T22:00:00+01:00'),
        ]);

        $dateFieldNormalizer = new DateFieldNormalizer($fieldNormalizer);

        self::assertSame(
            '2017-01-01T22:00:00+01:00',
            $dateFieldNormalizer->normalizeField(
                'date',
                $object,
                $context
            )
        );

        error_clear_last();
    }

    public function testNormalizeWithInvalidDateString()
    {
        $object = $this->getObject();
        $object->setDate('2017-01-01 25:00:00');

        /** @var NormalizerContextInterface|MockObject $context */
        $context = $this->getMockByCalls(NormalizerContextInterface::class);

        /** @var FieldNormalizerInterface|MockObject $fieldNormalizer */
        $fieldNormalizer = $this->getMockByCalls(FieldNormalizerInterface::class, [
            Call::create('normalizeField')
                ->with('date', $object, $context, null)
                ->willReturn('2017-01-01 25:00:00'),
        ]);

        $dateFieldNormalizer = new DateFieldNormalizer($fieldNormalizer);

        self::assertSame(
            '2017-01-01 25:00:00',
            $dateFieldNormalizer->normalizeField(
                'date',
                $object,
                $context
            )
        );

        error_clear_last();
    }

    public function testNormalizeWithNull()
    {
        $object = $this->getObject();

        /** @var NormalizerContextInterface|MockObject $context */
        $context = $this->getMockByCalls(NormalizerContextInterface::class);

        /** @var FieldNormalizerInterface|MockObject $fieldNormalizer */
        $fieldNormalizer = $this->getMockByCalls(FieldNormalizerInterface::class, [
            Call::create('normalizeField')
                ->with('date', $object, $context, null)
                ->willReturn(null),
        ]);

        $dateFieldNormalizer = new DateFieldNormalizer($fieldNormalizer);

        self::assertNull(
            $dateFieldNormalizer->normalizeField('date', $object, $context)
        );

        error_clear_last();
    }

    private function getObject()
    {
        return new class() {
            /**
             * @var \DateTime|string|null
             */
            private $date;

            /**
             * @return \DateTime|string|null
             */
            public function getDate()
            {
                return $this->date;
            }

            /**
             * @param \DateTime|string|null $date
             *
             * @return self
             */
            public function setDate($date): self
            {
                $this->date = $date;

                return $this;
            }
        };
    }
}
