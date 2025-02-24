<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization\Unit\Normalizer;

use Chubbyphp\Mock\MockMethod\WithCallback;
use Chubbyphp\Mock\MockMethod\WithException;
use Chubbyphp\Mock\MockMethod\WithReturn;
use Chubbyphp\Mock\MockObjectBuilder;
use Chubbyphp\Serialization\Mapping\NormalizationFieldMappingInterface;
use Chubbyphp\Serialization\Mapping\NormalizationLinkMappingInterface;
use Chubbyphp\Serialization\Mapping\NormalizationObjectMappingInterface;
use Chubbyphp\Serialization\Normalizer\FieldNormalizerInterface;
use Chubbyphp\Serialization\Normalizer\LinkNormalizerInterface;
use Chubbyphp\Serialization\Normalizer\Normalizer;
use Chubbyphp\Serialization\Normalizer\NormalizerContextInterface;
use Chubbyphp\Serialization\Normalizer\NormalizerObjectMappingRegistryInterface;
use Chubbyphp\Serialization\Policy\PolicyInterface;
use Chubbyphp\Serialization\SerializerLogicException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Chubbyphp\Serialization\Normalizer\Normalizer
 *
 * @internal
 */
final class NormalizerTest extends TestCase
{
    public function testNormalize(): void
    {
        $builder = new MockObjectBuilder();

        $object = $this->getObject();
        $object->setName('php');

        $class = $object::class;

        /** @var MockObject|PolicyInterface $namePolicy */
        $namePolicy = $builder->create(PolicyInterface::class, [
            new WithCallback('isCompliant', static function ($name, $obj, $context) use ($object) {
                self::assertSame('name', $name);
                self::assertSame($object, $obj);
                self::assertInstanceOf(NormalizerContextInterface::class, $context);

                return true;
            }),
        ]);

        /** @var FieldNormalizerInterface|MockObject $nameFieldNormalizer */
        $nameFieldNormalizer = $builder->create(FieldNormalizerInterface::class, [
            new WithCallback('normalizeField', static function ($field, $obj, $context, $normalizer) use ($object) {
                self::assertSame('name', $field);
                self::assertSame($object, $obj);
                self::assertInstanceOf(NormalizerContextInterface::class, $context);
                self::assertInstanceOf(Normalizer::class, $normalizer);

                return 'php';
            }),
        ]);

        /** @var MockObject|NormalizationFieldMappingInterface $nameFieldMapping */
        $nameFieldMapping = $builder->create(NormalizationFieldMappingInterface::class, [
            new WithReturn('getName', [], 'name'),
            new WithReturn('getPolicy', [], $namePolicy),
            new WithReturn('getFieldNormalizer', [], $nameFieldNormalizer),
        ]);

        /** @var MockObject|PolicyInterface $embeddedNamePolicy */
        $embeddedNamePolicy = $builder->create(PolicyInterface::class, [
            new WithCallback('isCompliant', static function ($name, $obj, $context) use ($object) {
                self::assertSame('name', $name);
                self::assertSame($object, $obj);
                self::assertInstanceOf(NormalizerContextInterface::class, $context);

                return true;
            }),
        ]);

        /** @var FieldNormalizerInterface|MockObject $embeddedNameFieldNormalizer */
        $embeddedNameFieldNormalizer = $builder->create(FieldNormalizerInterface::class, [
            new WithCallback('normalizeField', static function ($field, $obj, $context, $normalizer) use ($object) {
                self::assertSame('name', $field);
                self::assertSame($object, $obj);
                self::assertInstanceOf(NormalizerContextInterface::class, $context);
                self::assertInstanceOf(Normalizer::class, $normalizer);

                return 'php';
            }),
        ]);

        /** @var MockObject|NormalizationFieldMappingInterface $embeddedNameFieldMapping */
        $embeddedNameFieldMapping = $builder->create(NormalizationFieldMappingInterface::class, [
            new WithReturn('getName', [], 'name'),
            new WithReturn('getPolicy', [], $embeddedNamePolicy),
            new WithReturn('getFieldNormalizer', [], $embeddedNameFieldNormalizer),
        ]);

        /** @var MockObject|PolicyInterface $nameLinkPolicy */
        $nameLinkPolicy = $builder->create(PolicyInterface::class, [
            new WithCallback('isCompliant', static function ($name, $obj, $context) use ($object) {
                self::assertSame('name', $name);
                self::assertSame($object, $obj);
                self::assertInstanceOf(NormalizerContextInterface::class, $context);

                return true;
            }),
        ]);

        /** @var LinkNormalizerInterface|MockObject $nameLinkNormalizer */
        $nameLinkNormalizer = $builder->create(LinkNormalizerInterface::class, [
            new WithCallback('normalizeLink', static function ($linkName, $obj, $context) use ($object) {
                self::assertSame('', $linkName);
                self::assertSame($object, $obj);
                self::assertInstanceOf(NormalizerContextInterface::class, $context);

                return ['href' => '/api/model/id1'];
            }),
        ]);

        /** @var MockObject|NormalizationLinkMappingInterface $nameLinkMapping */
        $nameLinkMapping = $builder->create(NormalizationLinkMappingInterface::class, [
            new WithReturn('getName', [], 'name'),
            new WithReturn('getPolicy', [], $nameLinkPolicy),
            new WithReturn('getLinkNormalizer', [], $nameLinkNormalizer),
        ]);

        /** @var MockObject|NormalizationObjectMappingInterface $objectMapping */
        $objectMapping = $builder->create(NormalizationObjectMappingInterface::class, [
            new WithReturn('getNormalizationFieldMappings', [''], [$nameFieldMapping]),
            new WithReturn('getNormalizationEmbeddedFieldMappings', [''], [$embeddedNameFieldMapping]),
            new WithReturn('getNormalizationLinkMappings', [''], [$nameLinkMapping]),
            new WithReturn('getNormalizationType', [], 'object'),
        ]);

        /** @var MockObject|NormalizerObjectMappingRegistryInterface $objectMappingRegistry */
        $objectMappingRegistry = $builder->create(NormalizerObjectMappingRegistryInterface::class, [
            new WithReturn('getObjectMapping', [$class], $objectMapping),
        ]);

        $normalizer = new Normalizer($objectMappingRegistry);

        self::assertEquals(
            [
                'name' => 'php',
                '_embedded' => [
                    'name' => 'php',
                ],
                '_links' => [
                    'name' => [
                        'href' => '/api/model/id1',
                    ],
                ],
                '_type' => 'object',
            ],
            $normalizer->normalize($object)
        );
    }

    public function testNormalizeWithNonCompliantPolicy(): void
    {
        $builder = new MockObjectBuilder();

        $object = $this->getObject();
        $object->setName('php');

        $class = $object::class;

        /** @var MockObject|PolicyInterface $namePolicy */
        $namePolicy = $builder->create(PolicyInterface::class, [
            new WithCallback('isCompliant', static function ($name, $obj, $context) use ($object) {
                self::assertSame('name', $name);
                self::assertSame($object, $obj);
                self::assertInstanceOf(NormalizerContextInterface::class, $context);

                return false;
            }),
        ]);

        /** @var MockObject|NormalizationFieldMappingInterface $nameFieldMapping */
        $nameFieldMapping = $builder->create(NormalizationFieldMappingInterface::class, [
            new WithReturn('getName', [], 'name'),
            new WithReturn('getPolicy', [], $namePolicy),
        ]);

        /** @var MockObject|PolicyInterface $embeddedNamePolicy */
        $embeddedNamePolicy = $builder->create(PolicyInterface::class, [
            new WithCallback('isCompliant', static function ($name, $obj, $context) use ($object) {
                self::assertSame('name', $name);
                self::assertSame($object, $obj);
                self::assertInstanceOf(NormalizerContextInterface::class, $context);

                return false;
            }),
        ]);

        /** @var MockObject|NormalizationFieldMappingInterface $embeddedNameFieldMapping */
        $embeddedNameFieldMapping = $builder->create(NormalizationFieldMappingInterface::class, [
            new WithReturn('getName', [], 'name'),
            new WithReturn('getPolicy', [], $embeddedNamePolicy),
        ]);

        /** @var MockObject|PolicyInterface $nameLinkPolicy */
        $nameLinkPolicy = $builder->create(PolicyInterface::class, [
            new WithCallback('isCompliant', static function ($name, $obj, $context) use ($object) {
                self::assertSame('name', $name);
                self::assertSame($object, $obj);
                self::assertInstanceOf(NormalizerContextInterface::class, $context);

                return false;
            }),
        ]);

        /** @var MockObject|NormalizationLinkMappingInterface $nameLinkMapping */
        $nameLinkMapping = $builder->create(NormalizationLinkMappingInterface::class, [
            new WithReturn('getName', [], 'name'),
            new WithReturn('getPolicy', [], $nameLinkPolicy),
        ]);

        /** @var MockObject|NormalizationObjectMappingInterface $objectMapping */
        $objectMapping = $builder->create(NormalizationObjectMappingInterface::class, [
            new WithReturn('getNormalizationFieldMappings', [''], [$nameFieldMapping]),
            new WithReturn('getNormalizationEmbeddedFieldMappings', [''], [$embeddedNameFieldMapping]),
            new WithReturn('getNormalizationLinkMappings', [''], [$nameLinkMapping]),
            new WithReturn('getNormalizationType', [], 'object'),
        ]);

        /** @var MockObject|NormalizerObjectMappingRegistryInterface $objectMappingRegistry */
        $objectMappingRegistry = $builder->create(NormalizerObjectMappingRegistryInterface::class, [
            new WithReturn('getObjectMapping', [$class], $objectMapping),
        ]);

        $normalizer = new Normalizer($objectMappingRegistry);

        self::assertEquals(
            [
                '_type' => 'object',
            ],
            $normalizer->normalize($object)
        );
    }

    public function testNormalizeMissingMapping(): void
    {
        $this->expectException(SerializerLogicException::class);
        $this->expectExceptionMessage('There is no mapping for class: "stdClass"');

        $exception = SerializerLogicException::createMissingMapping(\stdClass::class);

        $builder = new MockObjectBuilder();

        /** @var MockObject|NormalizerObjectMappingRegistryInterface $objectMappingRegistry */
        $objectMappingRegistry = $builder->create(NormalizerObjectMappingRegistryInterface::class, [
            new WithException('getObjectMapping', [\stdClass::class], $exception),
        ]);

        $normalizer = new Normalizer($objectMappingRegistry);
        $normalizer->normalize(new \stdClass());
    }

    private function getObject(): object
    {
        return new class {
            private string $id = 'id1';

            private ?string $name = null;

            public function getId(): string
            {
                return $this->id;
            }

            public function getName(): ?string
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
