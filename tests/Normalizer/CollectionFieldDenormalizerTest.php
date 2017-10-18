<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization\Normalizer;

use Chubbyphp\Serialization\Accessor\AccessorInterface;
use Chubbyphp\Serialization\Normalizer\NormalizerContextInterface;
use Chubbyphp\Serialization\Normalizer\CollectionFieldNormalizer;
use Chubbyphp\Serialization\Normalizer\NormalizerInterface;
use Chubbyphp\Serialization\SerializerLogicException;
use Chubbyphp\Serialization\SerializerRuntimeException;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Chubbyphp\Serialization\Normalizer\CollectionFieldNormalizer
 */
class CollectionFieldNormalizerTest extends TestCase
{
    public function testDenormalizeFieldWithMissingNormalizer()
    {
        self::expectException(SerializerLogicException::class);
        self::expectExceptionMessage('There is no normalizer at path: "children"');

        $parent = $this->getParent();

        $fieldNormalizer = new CollectionFieldNormalizer(get_class($this->getChild()), $this->getAccessor());
        $fieldNormalizer->normalizeField('children', $parent, [['name' => 'name']], $this->getNormalizerContext());
    }

    public function testDenormalizeFieldWithoutArrayNormalizer()
    {
        self::expectException(SerializerRuntimeException::class);
        self::expectExceptionMessage('There is an invalid data type "NULL", needed "array" at path: "children"');

        $parent = $this->getParent();

        $fieldNormalizer = new CollectionFieldNormalizer(get_class($this->getChild()), $this->getAccessor());
        $fieldNormalizer->normalizeField(
            'children',
            $parent,
            null,
            $this->getNormalizerContext(),
            $this->getNormalizer()
        );
    }

    public function testDenormalizeFieldWithArrayButNullChildNormalizer()
    {
        self::expectException(SerializerRuntimeException::class);
        self::expectExceptionMessage('There is an invalid data type "array", needed "object" at path: "children[0]"');

        $parent = $this->getParent();

        $fieldNormalizer = new CollectionFieldNormalizer(get_class($this->getChild()), $this->getAccessor());
        $fieldNormalizer->normalizeField(
            'children',
            $parent,
            [null],
            $this->getNormalizerContext(),
            $this->getNormalizer()
        );
    }

    public function testDenormalizeFieldWithNewChild()
    {
        $parent = $this->getParent();

        $fieldNormalizer = new CollectionFieldNormalizer(get_class($this->getChild()), $this->getAccessor());
        $fieldNormalizer->normalizeField(
            'children',
            $parent,
            [['name' => 'name']],
            $this->getNormalizerContext(),
            $this->getNormalizer()
        );

        self::assertSame('name', $parent->getChildren()[0]->getName());
    }

    public function testDenormalizeFieldWithExistingChild()
    {
        $parent = $this->getParent();
        $parent->setChildren([$this->getChild()]);

        $fieldNormalizer = new CollectionFieldNormalizer(get_class($this->getChild()), $this->getAccessor());
        $fieldNormalizer->normalizeField(
            'children',
            $parent,
            [['name' => 'name']],
            $this->getNormalizerContext(),
            $this->getNormalizer()
        );

        self::assertSame('name', $parent->getChildren()[0]->getName());
    }

    /**
     * @return object
     */
    private function getParent()
    {
        return new class() {
            /**
             * @var array
             */
            private $children = [];

            /**
             * @return array
             */
            public function getChildren(): array
            {
                return $this->children;
            }

            /**
             * @param array $children
             *
             * @return self
             */
            public function setChildren(array $children): self
            {
                $this->children = $children;

                return $this;
            }
        };
    }

    /**
     * @return object
     */
    private function getChild()
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
     * @return AccessorInterface
     */
    private function getAccessor(): AccessorInterface
    {
        /** @var AccessorInterface|\PHPUnit_Framework_MockObject_MockObject $accessor */
        $accessor = $this->getMockBuilder(AccessorInterface::class)->getMockForAbstractClass();

        $accessor->expects(self::any())->method('setValue')->willReturnCallback(function ($object, $value) {
            $object->setChildren($value);
        });

        $accessor->expects(self::any())->method('getValue')->willReturnCallback(function ($object) {
            return $object->getChildren();
        });

        return $accessor;
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
     * @return NormalizerInterface
     */
    private function getNormalizer(): NormalizerInterface
    {
        /** @var NormalizerInterface|\PHPUnit_Framework_MockObject_MockObject $normalizer */
        $normalizer = $this->getMockBuilder(NormalizerInterface::class)->getMockForAbstractClass();

        $normalizer->expects(self::any())->method('normalize')->willReturnCallback(
            function ($object, array $data, NormalizerContextInterface $context = null, string $path = '') {
                if (is_string($object)) {
                    $object = $this->getChild();
                }

                $object->setName($data['name']);

                return $object;
            }
        );

        return $normalizer;
    }
}
