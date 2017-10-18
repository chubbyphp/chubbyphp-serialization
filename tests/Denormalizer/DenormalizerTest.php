<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization\Normalizer;

use Chubbyphp\Serialization\Normalizer\Normalizer;
use Chubbyphp\Serialization\Normalizer\NormalizerContextInterface;
use Chubbyphp\Serialization\Normalizer\NormalizerInterface;
use Chubbyphp\Serialization\Normalizer\NormalizerObjectMappingRegistryInterface;
use Chubbyphp\Serialization\Normalizer\FieldNormalizerInterface;
use Chubbyphp\Serialization\SerializerLogicException;
use Chubbyphp\Serialization\SerializerRuntimeException;
use Chubbyphp\Serialization\Mapping\NormalizationFieldMappingInterface;
use Chubbyphp\Serialization\Mapping\NormalizationObjectMappingInterface;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Chubbyphp\Serialization\Normalizer\Normalizer
 */
class NormalizerTest extends TestCase
{
    public function testDenormalizeWithNew()
    {
        $normalizer = new Normalizer($this->getNormalizerObjectMappingRegistry([
            $this->getNormalizationObjectMapping(),
        ]));

        $object = $normalizer->normalize(get_class($this->getObject()), ['name' => 'name']);

        self::assertSame('name', $object->getName());
    }

    public function testDenormalizeWithNewAndType()
    {
        $normalizer = new Normalizer($this->getNormalizerObjectMappingRegistry([
            $this->getNormalizationObjectMapping(),
        ]));

        $object = $normalizer->normalize(get_class($this->getObject()), ['name' => 'name', '_type' => 'object']);

        self::assertSame('name', $object->getName());
    }

    public function testDenormalizeWithExisting()
    {
        $normalizer = new Normalizer($this->getNormalizerObjectMappingRegistry([
            $this->getNormalizationObjectMapping(),
        ]));

        $object = $normalizer->normalize($this->getObject(), ['name' => 'name']);

        self::assertSame('name', $object->getName());
    }

    public function testDenormalizeWithAdditionalData()
    {
        self::expectException(SerializerRuntimeException::class);
        self::expectExceptionMessage('There are additional field(s) at paths: "value"');

        $normalizer = new Normalizer($this->getNormalizerObjectMappingRegistry([
            $this->getNormalizationObjectMapping(),
        ]));

        $normalizer->normalize(get_class($this->getObject()), ['name' => 'name', 'value' => 'value']);
    }

    public function testDenormalizeWithAdditionalDataAndAllowIt()
    {
        $normalizer = new Normalizer($this->getNormalizerObjectMappingRegistry([
            $this->getNormalizationObjectMapping(),
        ]));

        $object = $normalizer->normalize(
            get_class($this->getObject()),
            ['name' => 'name', 'value' => 'value'],
            $this->getNormalizerContext(true)
        );

        self::assertSame('name', $object->getName());
    }

    public function testDenormalizeWithMissingObjectMapping()
    {
        self::expectException(SerializerLogicException::class);

        $normalizer = new Normalizer($this->getNormalizerObjectMappingRegistry([]));

        $normalizer->normalize(get_class($this->getObject()), ['name' => 'name']);
    }

    public function testDenormalizeWithNoData()
    {
        $normalizer = new Normalizer($this->getNormalizerObjectMappingRegistry([
            $this->getNormalizationObjectMapping(),
        ]));

        $object = $normalizer->normalize(get_class($this->getObject()), []);

        self::assertNull($object->getName());
    }

    public function testDenormalizeWithGroups()
    {
        $normalizer = new Normalizer($this->getNormalizerObjectMappingRegistry([
            $this->getNormalizationObjectMapping(['read']),
        ]));

        $object = $normalizer->normalize(
            get_class($this->getObject()),
            ['name' => 'name'],
            $this->getNormalizerContext(false, ['read'])
        );

        self::assertSame('name', $object->getName());
    }

    public function testDenormalizeWithGroupsButNoGroupOnField()
    {
        $normalizer = new Normalizer($this->getNormalizerObjectMappingRegistry([
            $this->getNormalizationObjectMapping(),
        ]));

        $object = $normalizer->normalize(
            get_class($this->getObject()),
            ['name' => 'name'],
            $this->getNormalizerContext(false, ['read'])
        );

        self::assertNull($object->getName());
    }

    /**
     * @param NormalizationObjectMappingInterface[] $denormalizationObjectMappings
     *
     * @return NormalizerObjectMappingRegistryInterface
     */
    private function getNormalizerObjectMappingRegistry(array $denormalizationObjectMappings): NormalizerObjectMappingRegistryInterface
    {
        /** @var NormalizerObjectMappingRegistryInterface|\PHPUnit_Framework_MockObject_MockObject $objectMappingRegistry */
        $objectMappingRegistry = $this->getMockBuilder(NormalizerObjectMappingRegistryInterface::class)
            ->setMethods(['getObjectMapping'])
            ->getMockForAbstractClass();

        $objectMappingRegistry->__mapppings = [];

        foreach ($denormalizationObjectMappings as $denormalizationObjectMapping) {
            $objectMappingRegistry->__mapppings[$denormalizationObjectMapping->getClass()] = $denormalizationObjectMapping;
        }

        $objectMappingRegistry->expects(self::any())->method('getObjectMapping')->willReturnCallback(
            function (string $class) use ($objectMappingRegistry) {
                if (isset($objectMappingRegistry->__mapppings[$class])) {
                    return $objectMappingRegistry->__mapppings[$class];
                }

                throw SerializerLogicException::createMissingMapping($class);
            }
        );

        return $objectMappingRegistry;
    }

    /**
     * @param array $groups
     *
     * @return NormalizationObjectMappingInterface
     */
    private function getNormalizationObjectMapping(array $groups = []): NormalizationObjectMappingInterface
    {
        /** @var NormalizationObjectMappingInterface|\PHPUnit_Framework_MockObject_MockObject $objectMapping */
        $objectMapping = $this->getMockBuilder(NormalizationObjectMappingInterface::class)
            ->setMethods([])
            ->getMockForAbstractClass();

        $object = $this->getObject();

        $objectMapping->expects(self::any())->method('getClass')->willReturnCallback(
            function () use ($object) {
                return get_class($object);
            }
        );

        $objectMapping->expects(self::any())->method('getNormalizationFactory')->willReturnCallback(function () use ($object) {
            return function () use ($object) {
                return clone $object;
            };
        });

        $objectMapping->expects(self::any())->method('getNormalizationFieldMappings')->willReturn([
            $this->getNormalizationFieldMapping($groups),
        ]);

        return $objectMapping;
    }

    /**
     * @param array $groups
     *
     * @return NormalizationFieldMappingInterface
     */
    private function getNormalizationFieldMapping(array $groups = []): NormalizationFieldMappingInterface
    {
        /** @var NormalizationFieldMappingInterface|\PHPUnit_Framework_MockObject_MockObject $fieldMapping */
        $fieldMapping = $this->getMockBuilder(NormalizationFieldMappingInterface::class)
            ->setMethods([])
            ->getMockForAbstractClass();

        $fieldMapping->expects(self::any())->method('getName')->willReturn('name');
        $fieldMapping->expects(self::any())->method('getGroups')->willReturn($groups);
        $fieldMapping->expects(self::any())->method('getFieldNormalizer')->willReturn($this->getFieldNormalizer());

        return $fieldMapping;
    }

    /**
     * @return FieldNormalizerInterface
     */
    private function getFieldNormalizer(): FieldNormalizerInterface
    {
        /** @var FieldNormalizerInterface|\PHPUnit_Framework_MockObject_MockObject $fieldNormalizer */
        $fieldNormalizer = $this->getMockBuilder(FieldNormalizerInterface::class)
            ->setMethods([])
            ->getMockForAbstractClass();

        $fieldNormalizer->expects(self::any())->method('normalizeField')->willReturnCallback(function (
            string $path,
            $object,
            $value,
            NormalizerContextInterface $context,
            NormalizerInterface $normalizer = null
        ) {
            $object->setName($value);
        });

        return $fieldNormalizer;
    }

    /**
     * @param bool  $allowedAdditionalFields
     * @param array $groups
     *
     * @return NormalizerContextInterface
     */
    private function getNormalizerContext(
        bool $allowedAdditionalFields = false,
        array $groups = []
    ): NormalizerContextInterface {
        /** @var NormalizerContextInterface|\PHPUnit_Framework_MockObject_MockObject $context */
        $context = $this->getMockBuilder(NormalizerContextInterface::class)
            ->setMethods([])
            ->getMockForAbstractClass();

        $context->expects(self::any())->method('isAllowedAdditionalFields')->willReturn($allowedAdditionalFields);
        $context->expects(self::any())->method('getGroups')->willReturn($groups);

        return $context;
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
            private $name;

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
