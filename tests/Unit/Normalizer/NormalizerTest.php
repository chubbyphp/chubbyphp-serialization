<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization\Unit\Normalizer;

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
use Chubbyphp\Serialization\Policy\PolicyInterface;
use Chubbyphp\Serialization\SerializerLogicException;
use Chubbyphp\Tests\Serialization\Unit\Policy\PolicyIncludingPathTrait;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Chubbyphp\Serialization\Normalizer\Normalizer
 *
 * @internal
 */
final class NormalizerTest extends TestCase
{
    use MockByCallsTrait;
    use PolicyIncludingPathTrait;

    public function testNormalize(): void
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
            Call::create('getName')->with()->willReturn('name'),
            Call::create('getFieldNormalizer')->with()->willReturn($nameFieldNormalizer),
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
            Call::create('getName')->with()->willReturn('name'),
            Call::create('getFieldNormalizer')->with()->willReturn($embeddedNameFieldNormalizer),
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

    public function testNormalizeWithNullLink(): void
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

    public function testNormalizeWithMatchingGroup(): void
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
            Call::create('getName')->with()->willReturn('name'),
            Call::create('getGroups')->with()->willReturn(['group1']),
            Call::create('getFieldNormalizer')->with()->willReturn($nameFieldNormalizer),
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
            Call::create('getName')->with()->willReturn('name'),
            Call::create('getGroups')->with()->willReturn(['group1']),
            Call::create('getFieldNormalizer')->with()->willReturn($embeddedNameFieldNormalizer),
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

    public function testNormalizeWithNotMatchingGroup(): void
    {
        $object = $this->getObject();
        $object->setName('php');

        $class = get_class($object);

        /** @var NormalizationFieldMappingInterface|MockObject $nameFieldMapping */
        $nameFieldMapping = $this->getMockByCalls(NormalizationFieldMappingInterface::class, [
            Call::create('getName')->with()->willReturn('name'),
            Call::create('getGroups')->with()->willReturn(['group1']),
        ]);

        $fieldMappings = [$nameFieldMapping];

        /** @var NormalizationFieldMappingInterface|MockObject $embeddedNameFieldMapping */
        $embeddedNameFieldMapping = $this->getMockByCalls(NormalizationFieldMappingInterface::class, [
            Call::create('getName')->with()->willReturn('name'),
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

    public function testNormalizeWithCompliantPolicy(): void
    {
        $object = $this->getObject();
        $object->setName('php');

        $class = get_class($object);

        /** @var PolicyInterface|MockObject $compliantPolicy */
        $compliantPolicy = $this->getMockByCalls(PolicyInterface::class, [
            Call::create('isCompliant')
                ->with(new ArgumentInstanceOf(NormalizerContextInterface::class), $object)
                ->willReturn(true),
            Call::create('isCompliant')
                ->with(new ArgumentInstanceOf(NormalizerContextInterface::class), $object)
                ->willReturn(true),
            Call::create('isCompliant')
                ->with(new ArgumentInstanceOf(NormalizerContextInterface::class), $object)
                ->willReturn(true),
        ]);

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
        $nameFieldMapping = $this->getNormalizationFieldMappingWithPolicy(
            'name',
            $nameFieldNormalizer,
            $compliantPolicy
        );

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
        $embeddedNameFieldMapping = $this->getNormalizationFieldMappingWithPolicy(
            'name',
            $embeddedNameFieldNormalizer,
            $compliantPolicy
        );

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
        $nameLinkMapping = $this->getNormalizationLinkMappingWithPolicy(
            'name',
            $nameLinkNormalizer,
            $compliantPolicy
        );

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
        $object = $this->getObject();
        $object->setName('php');

        $class = get_class($object);

        /** @var PolicyInterface|MockObject $nonCompliantPolicy */
        $nonCompliantPolicy = $this->getMockByCalls(PolicyInterface::class, [
            Call::create('isCompliant')
                ->with(new ArgumentInstanceOf(NormalizerContextInterface::class), $object)
                ->willReturn(false),
            Call::create('isCompliant')
                ->with(new ArgumentInstanceOf(NormalizerContextInterface::class), $object)
                ->willReturn(false),
            Call::create('isCompliant')
                ->with(new ArgumentInstanceOf(NormalizerContextInterface::class), $object)
                ->willReturn(false),
        ]);

        /** @var FieldNormalizerInterface|MockObject $nameFieldNormalizer */
        $nameFieldNormalizer = $this->getMockByCalls(FieldNormalizerInterface::class, []);

        /** @var NormalizationFieldMappingInterface|MockObject $nameFieldMapping */
        $nameFieldMapping = $this->getNormalizationFieldMappingWithPolicy(
            'name',
            $nameFieldNormalizer,
            $nonCompliantPolicy
        );

        $fieldMappings = [$nameFieldMapping];

        /** @var FieldNormalizerInterface|MockObject $embeddedNameFieldNormalizer */
        $embeddedNameFieldNormalizer = $this->getMockByCalls(FieldNormalizerInterface::class, []);

        /** @var NormalizationFieldMappingInterface|MockObject $embeddedNameFieldMapping */
        $embeddedNameFieldMapping = $this->getNormalizationFieldMappingWithPolicy(
            'name',
            $embeddedNameFieldNormalizer,
            $nonCompliantPolicy
        );

        $embeddedFieldMappings = [$embeddedNameFieldMapping];

        /** @var LinkNormalizerInterface|MockObject $nameLinkNormalizer */
        $nameLinkNormalizer = $this->getMockByCalls(LinkNormalizerInterface::class, []);

        /** @var NormalizationLinkMappingInterface|MockObject $nameLinkMapping */
        $nameLinkMapping = $this->getNormalizationLinkMappingWithPolicy(
            'name',
            $nameLinkNormalizer,
            $nonCompliantPolicy
        );

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

        self::assertEquals(['_type' => 'object'], $normalizer->normalize($object));
    }

    public function testNormalizeWithCompliantIncludingPathPolicy(): void
    {
        $object = $this->getObject();
        $object->setName('php');

        $class = get_class($object);

        /** @var PolicyInterface|MockObject $compliantPolicy */
        $compliantPolicy = $this->getCompliantPolicyIncludingPath(true);

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
        $nameFieldMapping = $this->getNormalizationFieldMappingWithPolicy(
            'name',
            $nameFieldNormalizer,
            $compliantPolicy
        );

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
        $embeddedNameFieldMapping = $this->getNormalizationFieldMappingWithPolicy(
            'name',
            $embeddedNameFieldNormalizer,
            $compliantPolicy
        );

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
        $nameLinkMapping = $this->getNormalizationLinkMappingWithPolicy(
            'name',
            $nameLinkNormalizer,
            $compliantPolicy
        );

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

    public function testNormalizeWithNonCompliantIncludingPathPolicy(): void
    {
        $object = $this->getObject();
        $object->setName('php');

        $class = get_class($object);

        /** @var PolicyInterface|MockObject $nonCompliantPolicy */
        $nonCompliantPolicy = $this->getCompliantPolicyIncludingPath(false);

        /** @var FieldNormalizerInterface|MockObject $nameFieldNormalizer */
        $nameFieldNormalizer = $this->getMockByCalls(FieldNormalizerInterface::class, []);

        /** @var NormalizationFieldMappingInterface|MockObject $nameFieldMapping */
        $nameFieldMapping = $this->getNormalizationFieldMappingWithPolicy(
            'name',
            $nameFieldNormalizer,
            $nonCompliantPolicy
        );

        $fieldMappings = [$nameFieldMapping];

        /** @var FieldNormalizerInterface|MockObject $embeddedNameFieldNormalizer */
        $embeddedNameFieldNormalizer = $this->getMockByCalls(FieldNormalizerInterface::class, []);

        /** @var NormalizationFieldMappingInterface|MockObject $embeddedNameFieldMapping */
        $embeddedNameFieldMapping = $this->getNormalizationFieldMappingWithPolicy(
            'name',
            $embeddedNameFieldNormalizer,
            $nonCompliantPolicy
        );

        $embeddedFieldMappings = [$embeddedNameFieldMapping];

        /** @var LinkNormalizerInterface|MockObject $nameLinkNormalizer */
        $nameLinkNormalizer = $this->getMockByCalls(LinkNormalizerInterface::class, []);

        /** @var NormalizationLinkMappingInterface|MockObject $nameLinkMapping */
        $nameLinkMapping = $this->getNormalizationLinkMappingWithPolicy(
            'name',
            $nameLinkNormalizer,
            $nonCompliantPolicy
        );

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

        self::assertEquals(['_type' => 'object'], $normalizer->normalize($object));
    }

    public function testNormalizeMissingMapping(): void
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

            public function setName(string $name): self
            {
                $this->name = $name;

                return $this;
            }
        };
    }

    /**
     * @todo remove as soon getPolicy() is part of the mapping interface
     */
    private function getNormalizationFieldMappingWithPolicy(
        string $name,
        FieldNormalizerInterface $fieldNormalizer,
        PolicyInterface $policy,
        array $groups = []
    ): NormalizationFieldMappingInterface {
        return new class($name, $fieldNormalizer, $policy, $groups) implements NormalizationFieldMappingInterface {
            private $name;
            private $fieldNormalizer;
            private $policy;
            private $groups;

            public function __construct($name, $fieldNormalizer, $policy, $groups)
            {
                $this->name = $name;
                $this->fieldNormalizer = $fieldNormalizer;
                $this->policy = $policy;
                $this->groups = $groups;
            }

            public function getName(): string
            {
                return $this->name;
            }

            /**
             * @return string[]
             *
             * @deprecated
             */
            public function getGroups(): array
            {
                return $this->groups;
            }

            public function getFieldNormalizer(): FieldNormalizerInterface
            {
                return $this->fieldNormalizer;
            }

            public function getPolicy(): PolicyInterface
            {
                return $this->policy;
            }
        };
    }

    /**
     * @todo remove as soon getPolicy() is part of the mapping interface
     */
    private function getNormalizationLinkMappingWithPolicy(
        string $name,
        LinkNormalizerInterface $linkNormalizer,
        PolicyInterface $policy,
        array $groups = []
    ): NormalizationLinkMappingInterface {
        return new class($name, $linkNormalizer, $policy, $groups) implements NormalizationLinkMappingInterface {
            private $name;
            private $linkNormalizer;
            private $policy;
            private $groups;

            public function __construct($name, $linkNormalizer, $policy, $groups)
            {
                $this->name = $name;
                $this->linkNormalizer = $linkNormalizer;
                $this->policy = $policy;
                $this->groups = $groups;
            }

            public function getName(): string
            {
                return $this->name;
            }

            /**
             * @return string[]
             *
             * @deprecated
             */
            public function getGroups(): array
            {
                return $this->groups;
            }

            public function getLinkNormalizer(): LinkNormalizerInterface
            {
                return $this->linkNormalizer;
            }

            public function getPolicy(): PolicyInterface
            {
                return $this->policy;
            }
        };
    }
}
