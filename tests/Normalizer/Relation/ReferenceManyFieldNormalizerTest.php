<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization\Normalizer\Relation;

use Chubbyphp\Serialization\Accessor\AccessorInterface;
use Chubbyphp\Serialization\Normalizer\NormalizerContextInterface;
use Chubbyphp\Serialization\Normalizer\Relation\ReferenceManyFieldNormalizer;
use Chubbyphp\Serialization\Normalizer\NormalizerInterface;
use Doctrine\Common\Persistence\Proxy;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Chubbyphp\Serialization\Normalizer\Relation\ReferenceManyFieldNormalizer
 */
class ReferenceManyFieldNormalizerTest extends TestCase
{
    public function testNormalize()
    {
        $parent = $this->getParent();
        $parent->setChildren([
            $this->getChild('id1'),
            $this->getChild('id2'),
        ]);

        $fieldNormalizer = new ReferenceManyFieldNormalizer($this->getIdentifierAccessor(), $this->getAccessor());

        self::assertSame(
            ['id1', 'id2'],
            $fieldNormalizer->normalizeField(
                'children',
                $parent,
                $this->getNormalizerContext(),
                $this->getNormalizer()
            )
        );
    }

    public function testNormalizeDoctrineProxyCallsLoad()
    {
        $parent = $this->getParent();
        $parent->setChildren([
            $this->getDoctrineProxyChild(),
            $this->getDoctrineProxyChild(),
        ]);

        $fieldNormalizer = new ReferenceManyFieldNormalizer($this->getIdentifierAccessor(), $this->getAccessor());

        $fieldNormalizer->normalizeField(
            'children',
            $parent,
            $this->getNormalizerContext(),
            $this->getNormalizer()
        );
    }

    public function testNormalizeEmpty()
    {
        $parent = $this->getParent();
        $parent->setChildren([]);

        $fieldNormalizer = new ReferenceManyFieldNormalizer($this->getIdentifierAccessor(), $this->getAccessor());

        self::assertSame(
            [],
            $fieldNormalizer->normalizeField(
                'children',
                $parent,
                $this->getNormalizerContext(),
                $this->getNormalizer()
            )
        );
    }

    public function testNormalizeNull()
    {
        $parent = $this->getParent();

        $fieldNormalizer = new ReferenceManyFieldNormalizer($this->getIdentifierAccessor(), $this->getAccessor());

        self::assertNull(
            $fieldNormalizer->normalizeField(
                'children',
                $parent,
                $this->getNormalizerContext(),
                $this->getNormalizer()
            )
        );
    }

    /**
     * @return object
     */
    private function getParent()
    {
        return new class() {
            /**
             * @var array|null
             */
            private $children;

            /**
             * @return array
             */
            public function getChildren()
            {
                return $this->children;
            }

            /**
             * @param array|null $children
             *
             * @return self
             */
            public function setChildren(array $children = null): self
            {
                $this->children = $children;

                return $this;
            }
        };
    }

    /**
     * @param string|null $id
     *
     * @return object
     */
    private function getChild(string $id = null)
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

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|object
     */
    private function getDoctrineProxyChild()
    {
        $child = $this->getMockBuilder(Proxy::class)
            ->setMethods(['__load', '__isInitialized', 'getId'])
            ->getMockForAbstractClass();

        $child
            ->expects(self::once())
            ->method('__isInitialized');

        $child
            ->expects(self::once())
            ->method('__load');

        return $child;
    }

    /**
     * @return AccessorInterface
     */
    private function getAccessor(): AccessorInterface
    {
        /** @var AccessorInterface|\PHPUnit_Framework_MockObject_MockObject $accessor */
        $accessor = $this->getMockBuilder(AccessorInterface::class)->getMockForAbstractClass();

        $accessor->expects(self::any())->method('getValue')->willReturnCallback(function ($object) {
            return $object->getChildren();
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
            function ($object, NormalizerContextInterface $context = null, string $path = '') {
                return ['name' => $object->getName()];
            }
        );

        return $normalizer;
    }
}
