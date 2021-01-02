<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization\Unit\Normalizer\Relation;

use Chubbyphp\Mock\Call;
use Chubbyphp\Mock\MockByCallsTrait;
use Chubbyphp\Serialization\Accessor\AccessorInterface;
use Chubbyphp\Serialization\Normalizer\NormalizerContextInterface;
use Chubbyphp\Serialization\Normalizer\NormalizerInterface;
use Chubbyphp\Serialization\Normalizer\Relation\EmbedManyFieldNormalizer;
use Chubbyphp\Serialization\SerializerLogicException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Chubbyphp\Serialization\Normalizer\Relation\EmbedManyFieldNormalizer
 *
 * @internal
 */
final class EmbedManyFieldNormalizerTest extends TestCase
{
    use MockByCallsTrait;

    public function testNormalizeMissingNormalizer(): void
    {
        $this->expectException(SerializerLogicException::class);
        $this->expectExceptionMessage('There is no normalizer at path: "children"');

        /** @var AccessorInterface|MockObject $accessor */
        $accessor = $this->getMockByCalls(AccessorInterface::class);

        /** @var NormalizerContextInterface|MockObject $context */
        $context = $this->getMockByCalls(NormalizerContextInterface::class);

        $fieldNormalizer = new EmbedManyFieldNormalizer($accessor);

        $fieldNormalizer->normalizeField('children', new \stdClass(), $context);
    }

    public function testNormalize(): void
    {
        $child1 = $this->getChild();
        $child1->setName('name1');

        $child2 = $this->getChild();
        $child2->setName('name2');

        $parent = $this->getParent();
        $parent->setChildren([$child1, $child2]);

        /** @var AccessorInterface|MockObject $accessor */
        $accessor = $this->getMockByCalls(AccessorInterface::class, [
            Call::create('getValue')->with($parent)->willReturn($parent->getChildren()),
        ]);

        /** @var NormalizerContextInterface|MockObject $context */
        $context = $this->getMockByCalls(NormalizerContextInterface::class);

        /** @var NormalizerInterface|MockObject $normalizer */
        $normalizer = $this->getMockByCalls(NormalizerInterface::class, [
            Call::create('normalize')
                ->with($child1, $context, 'children[0]')
                ->willReturn(['name' => $child1->getName()]),
            Call::create('normalize')
                ->with($child2, $context, 'children[1]')
                ->willReturn(['name' => $child2->getName()]),
        ]);

        $fieldNormalizer = new EmbedManyFieldNormalizer($accessor);

        self::assertSame(
            [['name' => 'name1'], ['name' => 'name2']],
            $fieldNormalizer->normalizeField(
                'children',
                $parent,
                $context,
                $normalizer
            )
        );
    }

    public function testNormalizeEmpty(): void
    {
        $parent = $this->getParent();
        $parent->setChildren([]);

        /** @var AccessorInterface|MockObject $accessor */
        $accessor = $this->getMockByCalls(AccessorInterface::class, [
            Call::create('getValue')->with($parent)->willReturn($parent->getChildren()),
        ]);

        /** @var NormalizerContextInterface|MockObject $context */
        $context = $this->getMockByCalls(NormalizerContextInterface::class);

        /** @var NormalizerInterface|MockObject $normalizer */
        $normalizer = $this->getMockByCalls(NormalizerInterface::class);

        $fieldNormalizer = new EmbedManyFieldNormalizer($accessor);

        self::assertSame(
            [],
            $fieldNormalizer->normalizeField(
                'children',
                $parent,
                $context,
                $normalizer
            )
        );
    }

    public function testNormalizeNull(): void
    {
        $parent = $this->getParent();

        /** @var AccessorInterface|MockObject $accessor */
        $accessor = $this->getMockByCalls(AccessorInterface::class, [
            Call::create('getValue')->with($parent)->willReturn($parent->getChildren()),
        ]);

        /** @var NormalizerContextInterface|MockObject $context */
        $context = $this->getMockByCalls(NormalizerContextInterface::class);

        /** @var NormalizerInterface|MockObject $normalizer */
        $normalizer = $this->getMockByCalls(NormalizerInterface::class);

        $fieldNormalizer = new EmbedManyFieldNormalizer($accessor);

        self::assertNull(
            $fieldNormalizer->normalizeField(
                'children',
                $parent,
                $context,
                $normalizer
            )
        );
    }

    /**
     * @return object
     */
    private function getParent()
    {
        return new class() {
            private ?array $children = null;

            /**
             * @return array
             */
            public function getChildren()
            {
                return $this->children;
            }

            public function setChildren(array $children = null): self
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
            private ?string $name = null;

            public function getName(): string
            {
                return $this->name;
            }

            public function setName(string $name): self
            {
                $this->name = $name;

                return $this;
            }
        };
    }
}
