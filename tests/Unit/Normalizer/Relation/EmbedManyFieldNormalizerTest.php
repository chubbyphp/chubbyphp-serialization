<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization\Unit\Normalizer\Relation;

use Chubbyphp\Mock\MockMethod\WithReturn;
use Chubbyphp\Mock\MockObjectBuilder;
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
    public function testNormalizeMissingNormalizer(): void
    {
        $this->expectException(SerializerLogicException::class);
        $this->expectExceptionMessage('There is no normalizer at path: "children"');

        $builder = new MockObjectBuilder();

        /** @var AccessorInterface|MockObject $accessor */
        $accessor = $builder->create(AccessorInterface::class, []);

        /** @var MockObject|NormalizerContextInterface $context */
        $context = $builder->create(NormalizerContextInterface::class, []);

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

        $builder = new MockObjectBuilder();

        /** @var AccessorInterface|MockObject $accessor */
        $accessor = $builder->create(AccessorInterface::class, [
            new WithReturn('getValue', [$parent], $parent->getChildren()),
        ]);

        /** @var MockObject|NormalizerContextInterface $context */
        $context = $builder->create(NormalizerContextInterface::class, []);

        /** @var MockObject|NormalizerInterface $normalizer */
        $normalizer = $builder->create(NormalizerInterface::class, [
            new WithReturn('normalize', [$child1, $context, 'children[0]'], ['name' => $child1->getName()]),
            new WithReturn('normalize', [$child2, $context, 'children[1]'], ['name' => $child2->getName()]),
        ]);

        $fieldNormalizer = new EmbedManyFieldNormalizer($accessor);

        self::assertSame(
            [['name' => 'name1'], ['name' => 'name2']],
            $fieldNormalizer->normalizeField('children', $parent, $context, $normalizer)
        );
    }

    public function testNormalizeEmpty(): void
    {
        $parent = $this->getParent();
        $parent->setChildren([]);

        $builder = new MockObjectBuilder();

        /** @var AccessorInterface|MockObject $accessor */
        $accessor = $builder->create(AccessorInterface::class, [
            new WithReturn('getValue', [$parent], $parent->getChildren()),
        ]);

        /** @var MockObject|NormalizerContextInterface $context */
        $context = $builder->create(NormalizerContextInterface::class, []);

        /** @var MockObject|NormalizerInterface $normalizer */
        $normalizer = $builder->create(NormalizerInterface::class, []);

        $fieldNormalizer = new EmbedManyFieldNormalizer($accessor);

        self::assertSame(
            [],
            $fieldNormalizer->normalizeField('children', $parent, $context, $normalizer)
        );
    }

    public function testNormalizeNull(): void
    {
        $parent = $this->getParent();

        $builder = new MockObjectBuilder();

        /** @var AccessorInterface|MockObject $accessor */
        $accessor = $builder->create(AccessorInterface::class, [
            new WithReturn('getValue', [$parent], $parent->getChildren()),
        ]);

        /** @var MockObject|NormalizerContextInterface $context */
        $context = $builder->create(NormalizerContextInterface::class, []);

        /** @var MockObject|NormalizerInterface $normalizer */
        $normalizer = $builder->create(NormalizerInterface::class, []);

        $fieldNormalizer = new EmbedManyFieldNormalizer($accessor);

        self::assertNull(
            $fieldNormalizer->normalizeField('children', $parent, $context, $normalizer)
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

    private function getChild(): object
    {
        return new class {
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
