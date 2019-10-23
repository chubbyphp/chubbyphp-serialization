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

        /** @var NormalizerContextInterface|MockObject $context */
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

        /** @var NormalizerContextInterface|MockObject $context */
        $context = $this->getMockByCalls(NormalizerContextInterface::class);

        /** @var NormalizerInterface|MockObject $normalizer */
        $normalizer = $this->getMockByCalls(NormalizerInterface::class);

        $fieldNormalizer = new EmbedOneFieldNormalizer($accessor);

        $data = $fieldNormalizer->normalizeField(
            'relation',
            $object,
            $context,
            $normalizer
        );

        self::assertSame(null, $data);
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

        /** @var NormalizerContextInterface|MockObject $context */
        $context = $this->getMockByCalls(NormalizerContextInterface::class);

        /** @var NormalizerInterface|MockObject $normalizer */
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
     * @return object
     */
    private function getRelation()
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
}
