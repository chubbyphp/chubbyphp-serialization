<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization\Normalizer;

use Chubbyphp\Mock\Argument\ArgumentInstanceOf;
use Chubbyphp\Mock\Call;
use Chubbyphp\Mock\MockByCallsTrait;
use Chubbyphp\Serialization\Mapping\NormalizationFieldMappingInterface;
use Chubbyphp\Serialization\Mapping\NormalizationLinkMappingInterface;
use Chubbyphp\Serialization\Mapping\NormalizationObjectMappingInterface;
use Chubbyphp\Serialization\Normalizer\FieldNormalizerInterface;
use Chubbyphp\Serialization\Normalizer\LinkNormalizerInterface;
use Chubbyphp\Serialization\Normalizer\Normalizer;
use Chubbyphp\Serialization\Normalizer\NormalizerContextInterface;
use Chubbyphp\Serialization\Normalizer\NormalizerObjectMappingRegistryInterface;
use Chubbyphp\Serialization\SerializerLogicException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Chubbyphp\Serialization\Normalizer\Normalizer
 */
class NormalizerTest extends TestCase
{
    use MockByCallsTrait;

    public function testNormalize()
    {
        $object = $this->getObject();
        $object->setName('php');

        $class = get_class($object);

        /** @var FieldNormalizerInterface|MockObject $nameFieldNormalizer */
        $nameFieldNormalizer = $this->getMockByCalls(FieldNormalizerInterface::class, [
            Call::create('normalizeField')
                ->with(
                    'name',
                    $object,
                    new ArgumentInstanceOf(NormalizerContextInterface::class),
                    new ArgumentInstanceOf(Normalizer::class)
                )
                ->willReturn('php'),
        ]);

        /** @var NormalizationFieldMappingInterface|MockObject $nameFieldMapping */
        $nameFieldMapping = $this->getMockByCalls(NormalizationFieldMappingInterface::class, [
            //Call::create('getGroups')->with()->willReturn([]),
            Call::create('getFieldNormalizer')->with()->willReturn($nameFieldNormalizer),
            Call::create('getName')->with()->willReturn('name'),
        ]);

        $fieldMappings = [$nameFieldMapping];

        /** @var FieldNormalizerInterface|MockObject $embeddedNameFieldNormalizer */
        $embeddedNameFieldNormalizer = $this->getMockByCalls(FieldNormalizerInterface::class, [
            Call::create('normalizeField')
                ->with(
                    'name',
                    $object,
                    new ArgumentInstanceOf(NormalizerContextInterface::class),
                    new ArgumentInstanceOf(Normalizer::class)
                )
                ->willReturn('php'),
        ]);

        /** @var NormalizationFieldMappingInterface|MockObject $embeddedNameFieldMapping */
        $embeddedNameFieldMapping = $this->getMockByCalls(NormalizationFieldMappingInterface::class, [
            //Call::create('getGroups')->with()->willReturn([]),
            Call::create('getFieldNormalizer')->with()->willReturn($embeddedNameFieldNormalizer),
            Call::create('getName')->with()->willReturn('name'),
        ]);

        $embeddedFieldMappings = [$embeddedNameFieldMapping];

        /** @var LinkNormalizerInterface|MockObject $nameLinkNormalizer */
        $nameLinkNormalizer = $this->getMockByCalls(LinkNormalizerInterface::class, [
            Call::create('normalizeLink')
                ->with(
                    '',
                    $object,
                    new ArgumentInstanceOf(NormalizerContextInterface::class)
                )
                ->willReturn(['href' => '/api/model/id1']),
        ]);

        /** @var NormalizationLinkMappingInterface|MockObject $nameLinkMapping */
        $nameLinkMapping = $this->getMockByCalls(NormalizationLinkMappingInterface::class, [
            //Call::create('getGroups')->with()->willReturn([]),
            Call::create('getLinkNormalizer')->with()->willReturn($nameLinkNormalizer),
            Call::create('getName')->with()->willReturn('name'),
        ]);

        $linkMappings = [$nameLinkMapping];

        /** @var NormalizationObjectMappingInterface|MockObject $objectMapping */
        $objectMapping = $this->getMockByCalls(NormalizationObjectMappingInterface::class, [
            Call::create('getNormalizationFieldMappings')->with('')->willReturn($fieldMappings),
            Call::create('getNormalizationEmbeddedFieldMappings')->with('')->willReturn($embeddedFieldMappings),
            Call::create('getNormalizationLinkMappings')->with('')->willReturn($linkMappings),
            Call::create('getNormalizationType')->with()->willReturn('object'),
        ]);

        /** @var NormalizerObjectMappingRegistryInterface|MockObject $objectMappingRegistry */
        $objectMappingRegistry = $this->getMockByCalls(NormalizerObjectMappingRegistryInterface::class, [
            Call::create('getObjectMapping')->with($class)->willReturn($objectMapping),
        ]);

        $normalizer = new Normalizer($objectMappingRegistry);

        self::assertEquals([
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
        ], $normalizer->normalize($object));
    }

    public function testNormalizeWithNullLink()
    {
        $object = $this->getObject();
        $object->setName('php');

        $class = get_class($object);

        $fieldMappings = [];
        $embeddedFieldMappings = [];

        /** @var LinkNormalizerInterface|MockObject $nameLinkNormalizer */
        $nameLinkNormalizer = $this->getMockByCalls(LinkNormalizerInterface::class, [
            Call::create('normalizeLink')
                ->with(
                    '',
                    $object,
                    new ArgumentInstanceOf(NormalizerContextInterface::class)
                )
                ->willReturn(null),
        ]);

        /** @var NormalizationLinkMappingInterface|MockObject $nameLinkMapping */
        $nameLinkMapping = $this->getMockByCalls(NormalizationLinkMappingInterface::class, [
            Call::create('getLinkNormalizer')->with()->willReturn($nameLinkNormalizer),
        ]);

        $linkMappings = [$nameLinkMapping];

        /** @var NormalizationObjectMappingInterface|MockObject $objectMapping */
        $objectMapping = $this->getMockByCalls(NormalizationObjectMappingInterface::class, [
            Call::create('getNormalizationFieldMappings')->with('')->willReturn($fieldMappings),
            Call::create('getNormalizationEmbeddedFieldMappings')->with('')->willReturn($embeddedFieldMappings),
            Call::create('getNormalizationLinkMappings')->with('')->willReturn($linkMappings),
            Call::create('getNormalizationType')->with()->willReturn('object'),
        ]);

        /** @var NormalizerObjectMappingRegistryInterface|MockObject $objectMappingRegistry */
        $objectMappingRegistry = $this->getMockByCalls(NormalizerObjectMappingRegistryInterface::class, [
            Call::create('getObjectMapping')->with($class)->willReturn($objectMapping),
        ]);

        $normalizer = new Normalizer($objectMappingRegistry);

        self::assertEquals([
            '_type' => 'object',
        ], $normalizer->normalize($object));
    }

    public function testNormalizeWithMatchingGroup()
    {
        $object = $this->getObject();
        $object->setName('php');

        $class = get_class($object);

        /** @var FieldNormalizerInterface|MockObject $nameFieldNormalizer */
        $nameFieldNormalizer = $this->getMockByCalls(FieldNormalizerInterface::class, [
            Call::create('normalizeField')
                ->with(
                    'name',
                    $object,
                    new ArgumentInstanceOf(NormalizerContextInterface::class),
                    new ArgumentInstanceOf(Normalizer::class)
                )
                ->willReturn('php'),
        ]);

        /** @var NormalizationFieldMappingInterface|MockObject $nameFieldMapping */
        $nameFieldMapping = $this->getMockByCalls(NormalizationFieldMappingInterface::class, [
            Call::create('getGroups')->with()->willReturn(['group1']),
            Call::create('getFieldNormalizer')->with()->willReturn($nameFieldNormalizer),
            Call::create('getName')->with()->willReturn('name'),
        ]);

        $fieldMappings = [$nameFieldMapping];

        /** @var FieldNormalizerInterface|MockObject $embeddedNameFieldNormalizer */
        $embeddedNameFieldNormalizer = $this->getMockByCalls(FieldNormalizerInterface::class, [
            Call::create('normalizeField')
                ->with(
                    'name',
                    $object,
                    new ArgumentInstanceOf(NormalizerContextInterface::class),
                    new ArgumentInstanceOf(Normalizer::class)
                )
                ->willReturn('php'),
        ]);

        /** @var NormalizationFieldMappingInterface|MockObject $embeddedNameFieldMapping */
        $embeddedNameFieldMapping = $this->getMockByCalls(NormalizationFieldMappingInterface::class, [
            Call::create('getGroups')->with()->willReturn(['group1']),
            Call::create('getFieldNormalizer')->with()->willReturn($embeddedNameFieldNormalizer),
            Call::create('getName')->with()->willReturn('name'),
        ]);

        $embeddedFieldMappings = [$embeddedNameFieldMapping];

        /** @var LinkNormalizerInterface|MockObject $nameLinkNormalizer */
        $nameLinkNormalizer = $this->getMockByCalls(LinkNormalizerInterface::class, [
            Call::create('normalizeLink')
                ->with(
                    '',
                    $object,
                    new ArgumentInstanceOf(NormalizerContextInterface::class)
                )
                ->willReturn(['href' => '/api/model/id1']),
        ]);

        /** @var NormalizationLinkMappingInterface|MockObject $nameLinkMapping */
        $nameLinkMapping = $this->getMockByCalls(NormalizationLinkMappingInterface::class, [
            Call::create('getGroups')->with()->willReturn(['group1']),
            Call::create('getLinkNormalizer')->with()->willReturn($nameLinkNormalizer),
            Call::create('getName')->with()->willReturn('name'),
        ]);

        $linkMappings = [$nameLinkMapping];

        /** @var NormalizationObjectMappingInterface|MockObject $objectMapping */
        $objectMapping = $this->getMockByCalls(NormalizationObjectMappingInterface::class, [
            Call::create('getNormalizationFieldMappings')->with('')->willReturn($fieldMappings),
            Call::create('getNormalizationEmbeddedFieldMappings')->with('')->willReturn($embeddedFieldMappings),
            Call::create('getNormalizationLinkMappings')->with('')->willReturn($linkMappings),
            Call::create('getNormalizationType')->with()->willReturn('object'),
        ]);

        /** @var NormalizerObjectMappingRegistryInterface|MockObject $objectMappingRegistry */
        $objectMappingRegistry = $this->getMockByCalls(NormalizerObjectMappingRegistryInterface::class, [
            Call::create('getObjectMapping')->with($class)->willReturn($objectMapping),
        ]);

        /** @var NormalizerContextInterface|MockObject $context */
        $context = $this->getMockByCalls(NormalizerContextInterface::class, [
            Call::create('getGroups')->with()->willReturn(['group1']),
            Call::create('getGroups')->with()->willReturn(['group1']),
            Call::create('getGroups')->with()->willReturn(['group1']),
        ]);

        $normalizer = new Normalizer($objectMappingRegistry);

        self::assertEquals([
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
        ], $normalizer->normalize($object, $context));
    }

    public function testNormalizeWithNotMatchingGroup()
    {
        $object = $this->getObject();
        $object->setName('php');

        $class = get_class($object);

        /** @var NormalizationFieldMappingInterface|MockObject $nameFieldMapping */
        $nameFieldMapping = $this->getMockByCalls(NormalizationFieldMappingInterface::class, [
            Call::create('getGroups')->with()->willReturn(['group1']),
        ]);

        $fieldMappings = [$nameFieldMapping];

        /** @var NormalizationFieldMappingInterface|MockObject $embeddedNameFieldMapping */
        $embeddedNameFieldMapping = $this->getMockByCalls(NormalizationFieldMappingInterface::class, [
            Call::create('getGroups')->with()->willReturn(['group1']),
        ]);

        $embeddedFieldMappings = [$embeddedNameFieldMapping];

        /** @var NormalizationLinkMappingInterface|MockObject $nameLinkMapping */
        $nameLinkMapping = $this->getMockByCalls(NormalizationLinkMappingInterface::class, [
            Call::create('getGroups')->with()->willReturn(['group1']),
        ]);

        $linkMappings = [$nameLinkMapping];

        /** @var NormalizationObjectMappingInterface|MockObject $objectMapping */
        $objectMapping = $this->getMockByCalls(NormalizationObjectMappingInterface::class, [
            Call::create('getNormalizationFieldMappings')->with('')->willReturn($fieldMappings),
            Call::create('getNormalizationEmbeddedFieldMappings')->with('')->willReturn($embeddedFieldMappings),
            Call::create('getNormalizationLinkMappings')->with('')->willReturn($linkMappings),
            Call::create('getNormalizationType')->with()->willReturn('object'),
        ]);

        /** @var NormalizerObjectMappingRegistryInterface|MockObject $objectMappingRegistry */
        $objectMappingRegistry = $this->getMockByCalls(NormalizerObjectMappingRegistryInterface::class, [
            Call::create('getObjectMapping')->with($class)->willReturn($objectMapping),
        ]);

        /** @var NormalizerContextInterface|MockObject $context */
        $context = $this->getMockByCalls(NormalizerContextInterface::class, [
            Call::create('getGroups')->with()->willReturn(['group2']),
            Call::create('getGroups')->with()->willReturn(['group2']),
            Call::create('getGroups')->with()->willReturn(['group2']),
        ]);

        $normalizer = new Normalizer($objectMappingRegistry);

        self::assertEquals([
            '_type' => 'object',
        ], $normalizer->normalize($object, $context));
    }

    public function testNormalizeWithoutObject()
    {
        $this->expectException(SerializerLogicException::class);
        $this->expectExceptionMessage('Wrong data type "" at path : "string"');

        /** @var NormalizerObjectMappingRegistryInterface|MockObject $objectMappingRegistry */
        $objectMappingRegistry = $this->getMockByCalls(NormalizerObjectMappingRegistryInterface::class);

        $normalizer = new Normalizer($objectMappingRegistry);

        $normalizer->normalize('test');
    }

    public function testNormalizeMissingMapping()
    {
        $this->expectException(SerializerLogicException::class);
        $this->expectExceptionMessage('There is no mapping for class: "stdClass"');

        $exception = SerializerLogicException::createMissingMapping(\stdClass::class);

        /** @var NormalizerObjectMappingRegistryInterface|MockObject $objectMappingRegistry */
        $objectMappingRegistry = $this->getMockByCalls(NormalizerObjectMappingRegistryInterface::class, [
            Call::create('getObjectMapping')->with(\stdClass::class)->willThrowException($exception),
        ]);

        $normalizer = new Normalizer($objectMappingRegistry);

        $normalizer->normalize(new \stdClass());
    }

    /**
     * @return object
     */
    private function getObject()
    {
        return new class() {
            /**
             * @var string
             */
            private $id = 'id1';

            /**
             * @var string
             */
            private $name;

            /**
             * @return string
             */
            public function getId(): string
            {
                return $this->id;
            }

            /**
             * @return string|null
             */
            public function getName()
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
