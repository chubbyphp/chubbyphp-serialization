<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization\Normalizer;

use Chubbyphp\Serialization\Accessor\AccessorInterface;
use Chubbyphp\Serialization\Normalizer\DateTimeFieldNormalizer;
use Chubbyphp\Serialization\Normalizer\NormalizerContextInterface;
use Chubbyphp\Serialization\Normalizer\FieldNormalizerInterface;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Chubbyphp\Serialization\Normalizer\DateTimeFieldNormalizer
 */
class DateTimeFieldNormalizerTest extends TestCase
{
    public function testNormalizeFieldWithInvalidConstructArgument()
    {
        self::expectException(\TypeError::class);
        self::expectExceptionMessage('Chubbyphp\Serialization\Normalizer\DateTimeFieldNormalizer::__construct() expects parameter 1 to be Chubbyphp\Serialization\Accessor\AccessorInterface|Chubbyphp\Serialization\Normalizer\FieldNormalizerInterface, DateTime given');

        new DateTimeFieldNormalizer(new \DateTime());
    }
    
    public function testNormalizeFieldWithFieldNormalizer()
    {
        $object = $this->getObject();
        $object->setDate(new \DateTime('2017-01-01 22:00:00+01:00'));

        $fieldNormalizer = new DateTimeFieldNormalizer($this->getFieldNormalizer());

        self::assertSame(
            '2017-01-01T22:00:00+01:00',
            $fieldNormalizer->normalizeField(
                'date',
                $object,
                $this->getNormalizerContext()
            )
        );
    }
    
    public function testNormalizeField()
    {
        $object = $this->getObject();
        $object->setDate(new \DateTime('2017-01-01 22:00:00+01:00'));

        $fieldNormalizer = new DateTimeFieldNormalizer($this->getAccessor());

        self::assertSame(
            '2017-01-01T22:00:00+01:00',
            $fieldNormalizer->normalizeField(
                'date',
                $object,
                $this->getNormalizerContext()
            )
        );
    }

    public function testNormalizeWithValidDateString()
    {
        $object = $this->getObject();
        $object->setDate('2017-01-01 22:00:00+01:00');

        $fieldNormalizer = new DateTimeFieldNormalizer($this->getAccessor());

        self::assertSame(
            '2017-01-01T22:00:00+01:00',
            $fieldNormalizer->normalizeField(
                'date',
                $object,
                $this->getNormalizerContext()
            )
        );
    }

    public function testNormalizeWithInvalidDateString()
    {
        $object = $this->getObject();
        $object->setDate('2017-01-01 25:00:00');

        $fieldNormalizer = new DateTimeFieldNormalizer($this->getAccessor());

        self::assertSame(
            '2017-01-01 25:00:00',
            $fieldNormalizer->normalizeField(
                'date',
                $object,
                $this->getNormalizerContext()
            )
        );
    }

    public function testNormalizeWithNull()
    {
        $object = $this->getObject();

        $fieldNormalizer = new DateTimeFieldNormalizer($this->getAccessor());

        self::assertNull(
            $fieldNormalizer->normalizeField('date', $object, $this->getNormalizerContext())
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
        /** @var AccessorInterface|\PHPUnit_Framework_MockObject_MockObject $accessor */
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
        /** @var FieldNormalizerInterface|\PHPUnit_Framework_MockObject_MockObject $fieldNormalizer */
        $fieldNormalizer = $this->getMockBuilder(FieldNormalizerInterface::class)->getMockForAbstractClass();

        $fieldNormalizer->expects(self::any())->method('normalizeField')->willReturnCallback(
            function (string $path, $object) {
                return $object->getDate();
            }
        );

        return $fieldNormalizer;
    }

    /**
     * @return NormalizerContextInterface
     */
    private function getNormalizerContext(): NormalizerContextInterface
    {
        /** @var NormalizerContextInterface|\PHPUnit_Framework_MockObject_MockObject $context */
        $context = $this->getMockBuilder(NormalizerContextInterface::class)->getMockForAbstractClass();

        return $context;
    }
}
