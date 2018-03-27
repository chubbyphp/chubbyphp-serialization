<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization\Normalizer;

use Chubbyphp\Serialization\Normalizer\NormalizerContextInterface;
use Chubbyphp\Serialization\Normalizer\NormalizerInterface;
use Chubbyphp\Serialization\Normalizer\Relation\ReferenceOneFieldNormalizer;
use Chubbyphp\Serialization\Accessor\AccessorInterface;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Chubbyphp\Serialization\Normalizer\Relation\ReferenceOneFieldNormalizer
 */
class ReferenceOneFieldNormalizerTest extends TestCase
{
    public function testNormalizeFieldWithNull()
    {
        $object = $this->getObject();

        $fieldNormalizer = new ReferenceOneFieldNormalizer($this->getIdentifierAccessor(), $this->getAccessor());

        $data = $fieldNormalizer->normalizeField(
            'relation',
            $object,
            $this->getNormalizerContext(),
            $this->getNormalizer()
        );

        self::assertSame(null, $data);
    }

    public function testNormalizeFieldWithObject()
    {
        $object = $this->getObject();
        $object->setRelation($this->getRelation('id1'));

        $fieldNormalizer = new ReferenceOneFieldNormalizer($this->getIdentifierAccessor(), $this->getAccessor());

        $data = $fieldNormalizer->normalizeField(
            'relation',
            $object,
            $this->getNormalizerContext(),
            $this->getNormalizer()
        );

        self::assertSame('id1', $data);
    }

    /**
     * @return AccessorInterface
     */
    private function getAccessor(): AccessorInterface
    {
        /** @var AccessorInterface|\PHPUnit_Framework_MockObject_MockObject $accessor */
        $accessor = $this->getMockBuilder(AccessorInterface::class)->getMockForAbstractClass();

        $accessor->expects(self::any())->method('getValue')->willReturnCallback(function ($object) {
            return $object->getRelation();
        });

        return $accessor;
    }

    /**
     * @return AccessorInterface
     */
    private function getIdentifierAccessor(): AccessorInterface
    {
        /** @var AccessorInterface|\PHPUnit_Framework_MockObject_MockObject $accessor */
        $accessor = $this->getMockBuilder(AccessorInterface::class)->getMockForAbstractClass();

        $accessor->expects(self::any())->method('getValue')->willReturnCallback(function ($object) {
            return $object->getId();
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
            function ($object, NormalizerContextInterface $context = null, string $path = '') {
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
            private $relation;

            /**
             * @return object
             */
            public function getRelation()
            {
                return $this->relation;
            }

            /**
             * @param object $relation
             *
             * @return self
             */
            public function setRelation($relation): self
            {
                $this->relation = $relation;

                return $this;
            }
        };
    }

    /**
     * @param string|null $id
     *
     * @return object
     */
    private function getRelation(string $id)
    {
        return new class($id ?? uniqid()) {
            /**
             * @var string
             */
            private $id;

            /**
             * @param string $id
             */
            public function __construct(string $id)
            {
                $this->id = $id;
            }

            /**
             * @return string
             */
            public function getId(): string
            {
                return $this->id;
            }
        };
    }
}
