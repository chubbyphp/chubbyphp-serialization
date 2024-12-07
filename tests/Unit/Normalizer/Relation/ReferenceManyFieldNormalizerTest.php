<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization\Unit\Normalizer\Relation;

use Chubbyphp\Mock\Call;
use Chubbyphp\Mock\MockByCallsTrait;
use Chubbyphp\Serialization\Accessor\AccessorInterface;
use Chubbyphp\Serialization\Normalizer\NormalizerContextInterface;
use Chubbyphp\Serialization\Normalizer\Relation\ReferenceManyFieldNormalizer;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Chubbyphp\Serialization\Normalizer\Relation\ReferenceManyFieldNormalizer
 *
 * @internal
 */
final class ReferenceManyFieldNormalizerTest extends TestCase
{
    use MockByCallsTrait;

    public function testNormalize(): void
    {
        $child1 = $this->getChild('id1');
        $child2 = $this->getChild('id2');

        $parent = $this->getParent();
        $parent->setChildren([$child1, $child2]);

        /** @var AccessorInterface|MockObject $identifierAccessor */
        $identifierAccessor = $this->getMockByCalls(AccessorInterface::class, [
            Call::create('getValue')->with($child1)->willReturn($child1->getId()),
            Call::create('getValue')->with($child2)->willReturn($child2->getId()),
        ]);

        /** @var AccessorInterface|MockObject $accessor */
        $accessor = $this->getMockByCalls(AccessorInterface::class, [
            Call::create('getValue')->with($parent)->willReturn($parent->getChildren()),
        ]);

        /** @var MockObject|NormalizerContextInterface $context */
        $context = $this->getMockByCalls(NormalizerContextInterface::class);

        $fieldNormalizer = new ReferenceManyFieldNormalizer($identifierAccessor, $accessor);

        self::assertSame(
            ['id1', 'id2'],
            $fieldNormalizer->normalizeField(
                'children',
                $parent,
                $context
            )
        );
    }

    public function testNormalizeEmpty(): void
    {
        $parent = $this->getParent();
        $parent->setChildren([]);

        /** @var AccessorInterface|MockObject $identifierAccessor */
        $identifierAccessor = $this->getMockByCalls(AccessorInterface::class);

        /** @var AccessorInterface|MockObject $accessor */
        $accessor = $this->getMockByCalls(AccessorInterface::class, [
            Call::create('getValue')->with($parent)->willReturn($parent->getChildren()),
        ]);

        /** @var MockObject|NormalizerContextInterface $context */
        $context = $this->getMockByCalls(NormalizerContextInterface::class);

        $fieldNormalizer = new ReferenceManyFieldNormalizer($identifierAccessor, $accessor);

        self::assertSame(
            [],
            $fieldNormalizer->normalizeField(
                'children',
                $parent,
                $context
            )
        );
    }

    public function testNormalizeNull(): void
    {
        $parent = $this->getParent();

        /** @var AccessorInterface|MockObject $identifierAccessor */
        $identifierAccessor = $this->getMockByCalls(AccessorInterface::class);

        /** @var AccessorInterface|MockObject $accessor */
        $accessor = $this->getMockByCalls(AccessorInterface::class, [
            Call::create('getValue')->with($parent)->willReturn($parent->getChildren()),
        ]);

        /** @var MockObject|NormalizerContextInterface $context */
        $context = $this->getMockByCalls(NormalizerContextInterface::class);

        $fieldNormalizer = new ReferenceManyFieldNormalizer($identifierAccessor, $accessor);

        self::assertNull(
            $fieldNormalizer->normalizeField(
                'children',
                $parent,
                $context
            )
        );
    }

    private function getParent(): object
    {
        return new class {
            private ?array $children = null;

            public function getChildren(): ?array
            {
                return $this->children;
            }

            public function setChildren(?array $children = null): self
            {
                $this->children = $children;

                return $this;
            }
        };
    }

    private function getChild(?string $id = null): object
    {
        return new class($id ?? uniqid()) {
            public function __construct(private string $id) {}

            public function getId(): string
            {
                return $this->id;
            }
        };
    }
}
