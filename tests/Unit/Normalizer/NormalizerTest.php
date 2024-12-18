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

    public function testNormalize(): void
    {
        $object = $this->getObject();
        $object->setName('php');

        $class = $object::class;

        /** @var MockObject|PolicyInterface $namePolicy */
        $namePolicy = $this->getMockByCalls(PolicyInterface::class, [
            Call::create('isCompliant')
                ->with('name', $object, new ArgumentInstanceOf(NormalizerContextInterface::class))
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

        /** @var MockObject|NormalizationFieldMappingInterface $nameFieldMapping */
        $nameFieldMapping = $this->getMockByCalls(NormalizationFieldMappingInterface::class, [
            Call::create('getName')->with()->willReturn('name'),
            Call::create('getPolicy')->with()->willReturn($namePolicy),
            Call::create('getFieldNormalizer')->with()->willReturn($nameFieldNormalizer),
        ]);

        /** @var MockObject|PolicyInterface $embeddedNamePolicy */
        $embeddedNamePolicy = $this->getMockByCalls(PolicyInterface::class, [
            Call::create('isCompliant')
                ->with('name', $object, new ArgumentInstanceOf(NormalizerContextInterface::class))
                ->willReturn(true),
        ]);

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

        /** @var MockObject|NormalizationFieldMappingInterface $embeddedNameFieldMapping */
        $embeddedNameFieldMapping = $this->getMockByCalls(NormalizationFieldMappingInterface::class, [
            Call::create('getName')->with()->willReturn('name'),
            Call::create('getPolicy')->with()->willReturn($embeddedNamePolicy),
            Call::create('getFieldNormalizer')->with()->willReturn($embeddedNameFieldNormalizer),
        ]);

        /** @var MockObject|PolicyInterface $nameLinkPolicy */
        $nameLinkPolicy = $this->getMockByCalls(PolicyInterface::class, [
            Call::create('isCompliant')
                ->with('name', $object, new ArgumentInstanceOf(NormalizerContextInterface::class))
                ->willReturn(true),
        ]);

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

        /** @var MockObject|NormalizationLinkMappingInterface $nameLinkMapping */
        $nameLinkMapping = $this->getMockByCalls(NormalizationLinkMappingInterface::class, [
            Call::create('getName')->with()->willReturn('name'),
            Call::create('getPolicy')->with()->willReturn($nameLinkPolicy),
            Call::create('getLinkNormalizer')->with()->willReturn($nameLinkNormalizer),
        ]);

        /** @var MockObject|NormalizationObjectMappingInterface $objectMapping */
        $objectMapping = $this->getMockByCalls(NormalizationObjectMappingInterface::class, [
            Call::create('getNormalizationFieldMappings')->with('')->willReturn([$nameFieldMapping]),
            Call::create('getNormalizationEmbeddedFieldMappings')->with('')->willReturn([$embeddedNameFieldMapping]),
            Call::create('getNormalizationLinkMappings')->with('')->willReturn([$nameLinkMapping]),
            Call::create('getNormalizationType')->with()->willReturn('object'),
        ]);

        /** @var MockObject|NormalizerObjectMappingRegistryInterface $objectMappingRegistry */
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

        $class = $object::class;

        /** @var MockObject|PolicyInterface $namePolicy */
        $namePolicy = $this->getMockByCalls(PolicyInterface::class, [
            Call::create('isCompliant')
                ->with('name', $object, new ArgumentInstanceOf(NormalizerContextInterface::class))
                ->willReturn(false),
        ]);

        /** @var MockObject|NormalizationFieldMappingInterface $nameFieldMapping */
        $nameFieldMapping = $this->getMockByCalls(NormalizationFieldMappingInterface::class, [
            Call::create('getName')->with()->willReturn('name'),
            Call::create('getPolicy')->with()->willReturn($namePolicy),
        ]);

        /** @var MockObject|PolicyInterface $embeddedNamePolicy */
        $embeddedNamePolicy = $this->getMockByCalls(PolicyInterface::class, [
            Call::create('isCompliant')
                ->with('name', $object, new ArgumentInstanceOf(NormalizerContextInterface::class))
                ->willReturn(false),
        ]);

        /** @var MockObject|NormalizationFieldMappingInterface $embeddedNameFieldMapping */
        $embeddedNameFieldMapping = $this->getMockByCalls(NormalizationFieldMappingInterface::class, [
            Call::create('getName')->with()->willReturn('name'),
            Call::create('getPolicy')->with()->willReturn($embeddedNamePolicy),
        ]);

        /** @var MockObject|PolicyInterface $nameLinkPolicy */
        $nameLinkPolicy = $this->getMockByCalls(PolicyInterface::class, [
            Call::create('isCompliant')
                ->with('name', $object, new ArgumentInstanceOf(NormalizerContextInterface::class))
                ->willReturn(false),
        ]);

        /** @var MockObject|NormalizationLinkMappingInterface $nameLinkMapping */
        $nameLinkMapping = $this->getMockByCalls(NormalizationLinkMappingInterface::class, [
            Call::create('getName')->with()->willReturn('name'),
            Call::create('getPolicy')->with()->willReturn($nameLinkPolicy),
        ]);

        /** @var MockObject|NormalizationObjectMappingInterface $objectMapping */
        $objectMapping = $this->getMockByCalls(NormalizationObjectMappingInterface::class, [
            Call::create('getNormalizationFieldMappings')->with('')->willReturn([$nameFieldMapping]),
            Call::create('getNormalizationEmbeddedFieldMappings')->with('')->willReturn([$embeddedNameFieldMapping]),
            Call::create('getNormalizationLinkMappings')->with('')->willReturn([$nameLinkMapping]),
            Call::create('getNormalizationType')->with()->willReturn('object'),
        ]);

        /** @var MockObject|NormalizerObjectMappingRegistryInterface $objectMappingRegistry */
        $objectMappingRegistry = $this->getMockByCalls(NormalizerObjectMappingRegistryInterface::class, [
            Call::create('getObjectMapping')->with($class)->willReturn($objectMapping),
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

        /** @var MockObject|NormalizerObjectMappingRegistryInterface $objectMappingRegistry */
        $objectMappingRegistry = $this->getMockByCalls(NormalizerObjectMappingRegistryInterface::class, [
            Call::create('getObjectMapping')->with(\stdClass::class)->willThrowException($exception),
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
