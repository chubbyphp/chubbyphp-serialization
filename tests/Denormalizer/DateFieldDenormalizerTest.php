<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization\Normalizer;

use Chubbyphp\Serialization\Normalizer\DateFieldNormalizer;
use Chubbyphp\Serialization\Normalizer\NormalizerContextInterface;
use Chubbyphp\Serialization\Normalizer\FieldNormalizerInterface;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Chubbyphp\Serialization\Normalizer\DateFieldNormalizer
 */
class DateFieldNormalizerTest extends TestCase
{
    public function testDenormalizeField()
    {
        $object = $this->getObject();

        $fieldNormalizer = new DateFieldNormalizer($this->getFieldNormalizer());
        $fieldNormalizer->normalizeField('date', $object, '2017-01-01', $this->getNormalizerContext());

        self::assertSame('2017-01-01', $object->getDate()->format('Y-m-d'));
    }

    public function testDenormalizeInvalidField()
    {
        $object = $this->getObject();

        $fieldNormalizer = new DateFieldNormalizer($this->getFieldNormalizer());
        $fieldNormalizer->normalizeField('date', $object, '2017-13-01', $this->getNormalizerContext());

        self::assertSame('2017-13-01', $object->getDate());
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
     * @return FieldNormalizerInterface
     */
    private function getFieldNormalizer(): FieldNormalizerInterface
    {
        /** @var FieldNormalizerInterface|\PHPUnit_Framework_MockObject_MockObject $fieldNormalizer */
        $fieldNormalizer = $this->getMockBuilder(FieldNormalizerInterface::class)->getMockForAbstractClass();

        $fieldNormalizer->expects(self::any())->method('normalizeField')->willReturnCallback(
            function (string $path, $object, $value) {
                $object->setDate($value);
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
