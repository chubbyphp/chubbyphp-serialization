<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization\Normalizer;

use Chubbyphp\Mock\MockByCallsTrait;
use Chubbyphp\Serialization\Accessor\AccessorInterface;
use Chubbyphp\Serialization\Normalizer\DateTimeFieldNormalizer;
use Chubbyphp\Serialization\Normalizer\FieldNormalizerInterface;
use Chubbyphp\Serialization\Normalizer\NormalizerContextInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Chubbyphp\Serialization\Normalizer\DateTimeFieldNormalizer
 */
class DateTimeFieldNormalizerTest extends TestCase
{
    use MockByCallsTrait;

    public function testNormalizeFieldWithInvalidConstructArgument()
    {
        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(
            'Chubbyphp\Serialization\Normalizer\DateTimeFieldNormalizer::__construct() expects parameter 1 to be '
                .'Chubbyphp\Serialization\Accessor\AccessorInterface|Chubbyphp\Serialization\Normalizer\\'
                .'FieldNormalizerInterface, DateTime given'
        );

        new DateTimeFieldNormalizer(new \DateTime());
    }

    public function testNormalizeFieldWithFieldNormalizer()
    {
        $object = $this->getObject();
        $object->setDate(new \DateTime('2017-01-01 22:00:00+01:00'));

        /** @var NormalizerContextInterface|MockObject $context */
        $context = $this->getMockByCalls(NormalizerContextInterface::class);

        $fieldNormalizer = new DateTimeFieldNormalizer($this->getFieldNormalizer());

        self::assertSame(
            '2017-01-01T22:00:00+01:00',
            $fieldNormalizer->normalizeField(
                'date',
                $object,
                $context
            )
        );
    }

    public function testNormalizeField()
    {
        $object = $this->getObject();
        $object->setDate(new \DateTime('2017-01-01 22:00:00+01:00'));

        /** @var NormalizerContextInterface|MockObject $context */
        $context = $this->getMockByCalls(NormalizerContextInterface::class);

        $fieldNormalizer = new DateTimeFieldNormalizer($this->getAccessor());

        self::assertSame(
            '2017-01-01T22:00:00+01:00',
            $fieldNormalizer->normalizeField(
                'date',
                $object,
                $context
            )
        );
    }

    public function testNormalizeWithValidDateString()
    {
        $object = $this->getObject();
        $object->setDate('2017-01-01 22:00:00+01:00');

        /** @var NormalizerContextInterface|MockObject $context */
        $context = $this->getMockByCalls(NormalizerContextInterface::class);

        $fieldNormalizer = new DateTimeFieldNormalizer($this->getAccessor());

        self::assertSame(
            '2017-01-01T22:00:00+01:00',
            $fieldNormalizer->normalizeField(
                'date',
                $object,
                $context
            )
        );
    }

    public function testNormalizeWithInvalidDateString()
    {
        $object = $this->getObject();
        $object->setDate('2017-01-01 25:00:00');

        /** @var NormalizerContextInterface|MockObject $context */
        $context = $this->getMockByCalls(NormalizerContextInterface::class);

        $fieldNormalizer = new DateTimeFieldNormalizer($this->getAccessor());

        self::assertSame(
            '2017-01-01 25:00:00',
            $fieldNormalizer->normalizeField(
                'date',
                $object,
                $context
            )
        );
    }

    public function testNormalizeWithNull()
    {
        $object = $this->getObject();

        /** @var NormalizerContextInterface|MockObject $context */
        $context = $this->getMockByCalls(NormalizerContextInterface::class);

        $fieldNormalizer = new DateTimeFieldNormalizer($this->getAccessor());

        self::assertNull(
            $fieldNormalizer->normalizeField('date', $object, $context)
        );
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

    /**
     * @return AccessorInterface
     */
    private function getAccessor(): AccessorInterface
    {
        /** @var AccessorInterface|MockObject $accessor */
        $accessor = $this->getMockBuilder(AccessorInterface::class)->getMockForAbstractClass();

        $accessor->expects(self::any())->method('getValue')->willReturnCallback(
            function ($object) {
                return $object->getDate();
            }
        );

        return $accessor;
    }

    /**
     * @return FieldNormalizerInterface
     */
    private function getFieldNormalizer(): FieldNormalizerInterface
    {
        /** @var FieldNormalizerInterface|MockObject $fieldNormalizer */
        $fieldNormalizer = $this->getMockBuilder(FieldNormalizerInterface::class)->getMockForAbstractClass();

        $fieldNormalizer->expects(self::any())->method('normalizeField')->willReturnCallback(
            function (string $path, $object) {
                return $object->getDate();
            }
        );

        return $fieldNormalizer;
    }
}
