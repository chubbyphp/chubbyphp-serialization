<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization\Unit\Normalizer\Relation;

use Chubbyphp\Mock\MockMethod\WithReturn;
use Chubbyphp\Mock\MockObjectBuilder;
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
    public function testNormalize(): void
    {
        $child1 = $this->getChild('id1');
        $child2 = $this->getChild('id2');

        $parent = $this->getParent();
        $parent->setChildren([$child1, $child2]);

        $builder = new MockObjectBuilder();

        /** @var AccessorInterface|MockObject $identifierAccessor */
        $identifierAccessor = $builder->create(AccessorInterface::class, [
            new WithReturn('getValue', [$child1], $child1->getId()),
            new WithReturn('getValue', [$child2], $child2->getId()),
        ]);

        /** @var AccessorInterface|MockObject $accessor */
        $accessor = $builder->create(AccessorInterface::class, [
            new WithReturn('getValue', [$parent], $parent->getChildren()),
        ]);

        /** @var MockObject|NormalizerContextInterface $context */
        $context = $builder->create(NormalizerContextInterface::class, []);

        $fieldNormalizer = new ReferenceManyFieldNormalizer($identifierAccessor, $accessor);

        self::assertSame(
            ['id1', 'id2'],
            $fieldNormalizer->normalizeField('children', $parent, $context)
        );
    }

    public function testNormalizeEmpty(): void
    {
        $parent = $this->getParent();
        $parent->setChildren([]);

        $builder = new MockObjectBuilder();

        /** @var AccessorInterface|MockObject $identifierAccessor */
        $identifierAccessor = $builder->create(AccessorInterface::class, []);

        /** @var AccessorInterface|MockObject $accessor */
        $accessor = $builder->create(AccessorInterface::class, [
            new WithReturn('getValue', [$parent], $parent->getChildren()),
        ]);

        /** @var MockObject|NormalizerContextInterface $context */
        $context = $builder->create(NormalizerContextInterface::class, []);

        $fieldNormalizer = new ReferenceManyFieldNormalizer($identifierAccessor, $accessor);

        self::assertSame(
            [],
            $fieldNormalizer->normalizeField('children', $parent, $context)
        );
    }

    public function testNormalizeNull(): void
    {
        $parent = $this->getParent();

        $builder = new MockObjectBuilder();

        /** @var AccessorInterface|MockObject $identifierAccessor */
        $identifierAccessor = $builder->create(AccessorInterface::class, []);

        /** @var AccessorInterface|MockObject $accessor */
        $accessor = $builder->create(AccessorInterface::class, [
            new WithReturn('getValue', [$parent], $parent->getChildren()),
        ]);

        /** @var MockObject|NormalizerContextInterface $context */
        $context = $builder->create(NormalizerContextInterface::class, []);

        $fieldNormalizer = new ReferenceManyFieldNormalizer($identifierAccessor, $accessor);

        self::assertNull(
            $fieldNormalizer->normalizeField('children', $parent, $context)
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
