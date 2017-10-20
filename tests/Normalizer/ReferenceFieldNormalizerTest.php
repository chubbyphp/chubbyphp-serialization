<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization\Normalizer;

use Chubbyphp\Serialization\Normalizer\NormalizerContextInterface;
use Chubbyphp\Serialization\Normalizer\NormalizerInterface;
use Chubbyphp\Serialization\Normalizer\ReferenceFieldNormalizer;
use Chubbyphp\Serialization\Accessor\AccessorInterface;
use Chubbyphp\Serialization\SerializerLogicException;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * @covers \Chubbyphp\Serialization\Normalizer\ReferenceFieldNormalizer
 */
class ReferenceFieldNormalizerTest extends TestCase
{
    public function testNormalizeFieldWithMissingNormalizer()
    {
        self::expectException(SerializerLogicException::class);
        self::expectExceptionMessage('There is no normalizer at path: "reference"');

        $object = $this->getObject();

        $fieldNormalizer = new ReferenceFieldNormalizer($this->getAccessor());

        $fieldNormalizer->normalizeField(
            'reference',
            $this->getRequest(),
            $object,
            $this->getNormalizerContext()
        );
    }

    public function testNormalizeFieldWithNull()
    {
        $object = $this->getObject();

        $fieldNormalizer = new ReferenceFieldNormalizer($this->getAccessor());

        $data = $fieldNormalizer->normalizeField(
            'reference',
            $this->getRequest(),
            $object,
            $this->getNormalizerContext(),
            $this->getNormalizer()
        );

        self::assertSame(null, $data);
    }

    public function testNormalizeFieldWithObject()
    {
        $object = $this->getObject();
        $object->setReference($this->getReference()->setName('php'));

        $fieldNormalizer = new ReferenceFieldNormalizer($this->getAccessor());

        $data = $fieldNormalizer->normalizeField(
            'reference',
            $this->getRequest(),
            $object,
            $this->getNormalizerContext(),
            $this->getNormalizer()
        );

        self::assertSame(['name' => 'php'], $data);
    }

    /**
     * @return AccessorInterface
     */
    private function getAccessor(): AccessorInterface
    {
        /** @var AccessorInterface|\PHPUnit_Framework_MockObject_MockObject $accessor */
        $accessor = $this->getMockBuilder(AccessorInterface::class)->getMockForAbstractClass();

        $accessor->expects(self::any())->method('getValue')->willReturnCallback(function ($object) {
            return $object->getReference();
        });

        return $accessor;
    }

    /**
     * @return NormalizerInterface
     */
    private function getNormalizer(): NormalizerInterface
    {
        /** @var NormalizerInterface|\PHPUnit_Framework_MockObject_MockObject $normalizer */
        $normalizer = $this->getMockBuilder(NormalizerInterface::class)->getMockForAbstractClass();

        $normalizer->expects(self::any())->method('normalize')->willReturnCallback(
            function (Request $request, $object, NormalizerContextInterface $context = null, string $path = '') {
                return ['name' => $object->getName()];
            }
        );

        return $normalizer;
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

    /**
     * @return object
     */
    private function getObject()
    {
        return new class() {
            /**
             * @var object
             */
            private $reference;

            /**
             * @return object
             */
            public function getReference()
            {
                return $this->reference;
            }

            /**
             * @param object $reference
             *
             * @return self
             */
            public function setReference($reference): self
            {
                $this->reference = $reference;

                return $this;
            }
        };
    }

    /**
     * @return object
     */
    private function getReference()
    {
        return new class() {
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
    }

    /**
     * @return Request
     */
    private function getRequest(): Request
    {
        /** @var Request|\PHPUnit_Framework_MockObject_MockObject $request */
        $request = $this->getMockBuilder(Request::class)->getMockForAbstractClass();

        return $request;
    }
}
