<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization\Normalizer;

use Chubbyphp\Serialization\Normalizer\NormalizerContextInterface;
use Chubbyphp\Serialization\Normalizer\CallbackFieldNormalizer;
use Chubbyphp\Serialization\Normalizer\NormalizerInterface;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Chubbyphp\Serialization\Normalizer\CallbackFieldNormalizer
 */
class CallbackFieldNormalizerTest extends TestCase
{
    public function testNormalizeField()
    {
        $object = new class() {
            /**
             * @var string
             */
            private $name;

            /**
             * @return string
             */
            public function getName(): string
            {
                return $this->name;
            }

            /**
             * @param string $name
             *
             * @return self
             */
            public function setName(string $name): self
            {
                $this->name = $name;

                return $this;
            }
        };

        $object->setName('name');

        $fieldNormalizer = new CallbackFieldNormalizer(
            function (
                string $path,
                $object,
                NormalizerContextInterface $context,
                NormalizerInterface $normalizer = null
            ) {
                return $object->getName();
            }
        );

        self::assertSame('name', $fieldNormalizer->normalizeField('name', $object, $this->getNormalizerContext()));
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
