<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization\Unit\Normalizer;

use Chubbyphp\Mock\Call;
use Chubbyphp\Mock\MockByCallsTrait;
use Chubbyphp\Serialization\Accessor\AccessorInterface;
use Chubbyphp\Serialization\Normalizer\NormalizerContextInterface;
use Chubbyphp\Serialization\Normalizer\NormalizerInterface;
use Chubbyphp\Serialization\Normalizer\Relation\EmbedOneFieldNormalizer;
use Chubbyphp\Serialization\SerializerLogicException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Chubbyphp\Serialization\Normalizer\Relation\EmbedOneFieldNormalizer
 *
 * @internal
 */
final class EmbedOneFieldNormalizerTest extends TestCase
{
    use MockByCallsTrait;

    public function testNormalizeFieldWithMissingNormalizer(): void
    {
        $this->expectException(SerializerLogicException::class);
        $this->expectExceptionMessage('There is no normalizer at path: "relation"');

        $object = $this->getObject();

        /** @var AccessorInterface|MockObject $accessor */
        $accessor = $this->getMockByCalls(AccessorInterface::class);

        /** @var MockObject|NormalizerContextInterface $context */
        $context = $this->getMockByCalls(NormalizerContextInterface::class);

        $fieldNormalizer = new EmbedOneFieldNormalizer($accessor);

        $fieldNormalizer->normalizeField('relation', $object, $context);
    }

    public function testNormalizeFieldWithNull(): void
    {
        $object = $this->getObject();

        /** @var AccessorInterface|MockObject $accessor */
        $accessor = $this->getMockByCalls(AccessorInterface::class, [
            Call::create('getValue')->with($object)->willReturn(null),
        ]);

        /** @var MockObject|NormalizerContextInterface $context */
        $context = $this->getMockByCalls(NormalizerContextInterface::class);

        /** @var MockObject|NormalizerInterface $normalizer */
        $normalizer = $this->getMockByCalls(NormalizerInterface::class);

        $fieldNormalizer = new EmbedOneFieldNormalizer($accessor);

        $data = $fieldNormalizer->normalizeField(
            'relation',
            $object,
            $context,
            $normalizer
        );

        self::assertNull($data);
    }

    public function testNormalizeFieldWithObject(): void
    {
        $relation = $this->getRelation();
        $relation->setName('php');

        $object = $this->getObject();
        $object->setRelation($relation);

        /** @var AccessorInterface|MockObject $accessor */
        $accessor = $this->getMockByCalls(AccessorInterface::class, [
            Call::create('getValue')->with($object)->willReturn($relation),
        ]);

        /** @var MockObject|NormalizerContextInterface $context */
        $context = $this->getMockByCalls(NormalizerContextInterface::class);

        /** @var MockObject|NormalizerInterface $normalizer */
        $normalizer = $this->getMockByCalls(NormalizerInterface::class, [
            Call::create('normalize')
                ->with($relation, $context, 'relation')
                ->willReturn(['name' => $relation->getName()]),
        ]);

        $fieldNormalizer = new EmbedOneFieldNormalizer($accessor);

        $data = $fieldNormalizer->normalizeField(
            'relation',
            $object,
            $context,
            $normalizer
        );

        self::assertSame(['name' => 'php'], $data);
    }

    private function getObject(): object
    {
        return new class() {
            private object $relation;

            public function getRelation(): object
            {
                return $this->relation;
            }

            public function setRelation(object $relation): self
            {
                $this->relation = $relation;

                return $this;
            }
        };
    }

    private function getRelation(): object
    {
        return new class() {
            private string $name;

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
