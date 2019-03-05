<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization\Normalizer;

use Chubbyphp\Serialization\Accessor\AccessorInterface;
use Chubbyphp\Serialization\Normalizer\NormalizerContextInterface;
use Chubbyphp\Serialization\Normalizer\NormalizerInterface;
use Chubbyphp\Serialization\Normalizer\Relation\EmbedOneFieldNormalizer;
use Chubbyphp\Serialization\SerializerLogicException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Chubbyphp\Serialization\Normalizer\Relation\EmbedOneFieldNormalizer
 */
class EmbedOneFieldNormalizerTest extends TestCase
{
    public function testNormalizeFieldWithMissingNormalizer()
    {
        $this->expectException(SerializerLogicException::class);
        $this->expectExceptionMessage('There is no normalizer at path: "relation"');

        $object = $this->getObject();

        $fieldNormalizer = new EmbedOneFieldNormalizer($this->getAccessor());

        $fieldNormalizer->normalizeField('relation', $object, $this->getNormalizerContext());
    }

    public function testNormalizeFieldWithNull()
    {
        $object = $this->getObject();

        $fieldNormalizer = new EmbedOneFieldNormalizer($this->getAccessor());

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
        $object->setRelation($this->getRelation()->setName('php'));

        $fieldNormalizer = new EmbedOneFieldNormalizer($this->getAccessor());

        $data = $fieldNormalizer->normalizeField(
            'relation',
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
        /** @var AccessorInterface|MockObject $accessor */
        $accessor = $this->getMockBuilder(AccessorInterface::class)->getMockForAbstractClass();

        $accessor->expects(self::any())->method('getValue')->willReturnCallback(function ($object) {
            return $object->getRelation();
        });

        return $accessor;
    }

    /**
     * @return NormalizerInterface
     */
    private function getNormalizer(): NormalizerInterface
    {
        /** @var NormalizerInterface|MockObject $normalizer */
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
        /** @var NormalizerContextInterface|MockObject $context */
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
