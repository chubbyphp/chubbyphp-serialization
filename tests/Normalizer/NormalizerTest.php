<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization\Normalizer;

use Chubbyphp\Serialization\Mapping\NormalizationFieldMappingInterface;
use Chubbyphp\Serialization\Mapping\NormalizationLinkMappingInterface;
use Chubbyphp\Serialization\Mapping\NormalizationObjectMappingInterface;
use Chubbyphp\Serialization\Normalizer\FieldNormalizerInterface;
use Chubbyphp\Serialization\Normalizer\LinkNormalizerInterface;
use Chubbyphp\Serialization\Normalizer\Normalizer;
use Chubbyphp\Serialization\Normalizer\NormalizerContextInterface;
use Chubbyphp\Serialization\Normalizer\NormalizerInterface;
use Chubbyphp\Serialization\Normalizer\NormalizerObjectMappingRegistryInterface;
use Chubbyphp\Serialization\SerializerLogicException;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * @covers \Chubbyphp\Serialization\Normalizer\Normalizer
 */
class NormalizerTest extends TestCase
{
    public function testNormalize()
    {
        $object = $this->getObject();
        $object->setName('php');

        $normalizer = new Normalizer(
            $this->getNormalizerObjectMappingRegistry([
                $this->getNormalizationObjectMapping(),
            ])
        );

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
        ], $normalizer->normalize($this->getRequest(), $object));
    }

    public function testNormalizeWithoutObject()
    {
        self::expectException(SerializerLogicException::class);
        self::expectExceptionMessage('Wrong data type "" at path : "string"');

        $normalizer = new Normalizer($this->getNormalizerObjectMappingRegistry([]));

        $normalizer->normalize($this->getRequest(), 'test');
    }

    public function testNormalizeMissingMapping()
    {
        self::expectException(SerializerLogicException::class);
        self::expectExceptionMessage('There is no mapping for class: "stdClass"');

        $normalizer = new Normalizer(
            $this->getNormalizerObjectMappingRegistry([
                $this->getNormalizationObjectMapping(),
            ])
        );

        $normalizer->normalize($this->getRequest(), new \stdClass());
    }

    public function testNormalizeWithGroup()
    {
        $object = $this->getObject();
        $object->setName('php');

        $normalizer = new Normalizer(
            $this->getNormalizerObjectMappingRegistry([
                $this->getNormalizationObjectMapping(['group1'], ['group2'], ['group2']),
            ])
        );

        self::assertEquals([
            'name' => 'php',
            '_type' => 'object',
        ], $normalizer->normalize($this->getRequest(), $object, $this->getNormalizerContext(['group1'])));
    }

    public function testNormalizeWithNullLink()
    {
        $object = $this->getObject();
        $object->setName('php');

        $normalizer = new Normalizer(
            $this->getNormalizerObjectMappingRegistry([
                $this->getNormalizationObjectMapping([], [], [], true),
            ])
        );

        self::assertEquals([
            'name' => 'php',
            '_embedded' => [
                'name' => 'php',
            ],
            '_type' => 'object',
        ], $normalizer->normalize($this->getRequest(), $object));
    }

    /**
     * @param NormalizationObjectMappingInterface[] $normalizationObjectMappings
     *
     * @return NormalizerObjectMappingRegistryInterface
     */
    private function getNormalizerObjectMappingRegistry(array $normalizationObjectMappings): NormalizerObjectMappingRegistryInterface
    {
        /** @var NormalizerObjectMappingRegistryInterface|\PHPUnit_Framework_MockObject_MockObject $objectMappingRegistry */
        $objectMappingRegistry = $this->getMockBuilder(NormalizerObjectMappingRegistryInterface::class)
            ->setMethods(['getObjectMapping'])
            ->getMockForAbstractClass();

        $objectMappingRegistry->__mapppings = [];

        foreach ($normalizationObjectMappings as $normalizationObjectMapping) {
            $objectMappingRegistry->__mapppings[$normalizationObjectMapping->getClass()] = $normalizationObjectMapping;
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
     * @param array $groupFields
     * @param array $groupEmbeddedFields
     * @param array $groupLinks
     * @param bool  $nullLink
     *
     * @return NormalizationObjectMappingInterface
     */
    private function getNormalizationObjectMapping(
        array $groupFields = [],
        array $groupEmbeddedFields = [],
        array $groupLinks = [],
        bool $nullLink = false
    ): NormalizationObjectMappingInterface {
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

        $objectMapping->expects(self::any())->method('getNormalizationType')->willReturn('object');

        $objectMapping->expects(self::any())->method('getNormalizationFieldMappings')->willReturn([
            $this->getNormalizationFieldMapping($groupFields),
        ]);

        $objectMapping->expects(self::any())->method('getNormalizationEmbeddedFieldMappings')->willReturn([
            $this->getNormalizationFieldMapping($groupEmbeddedFields),
        ]);

        $objectMapping->expects(self::any())->method('getNormalizationLinkMappings')->willReturn([
            $this->getNormalizationLinkMapping($nullLink, $groupLinks),
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
     * @param bool  $nullLink
     * @param array $groups
     *
     * @return NormalizationLinkMappingInterface
     */
    private function getNormalizationLinkMapping(bool $nullLink, array $groups = []): NormalizationLinkMappingInterface
    {
        /** @var NormalizationLinkMappingInterface|\PHPUnit_Framework_MockObject_MockObject $linkMapping */
        $linkMapping = $this->getMockBuilder(NormalizationLinkMappingInterface::class)
            ->setMethods([])
            ->getMockForAbstractClass();

        $linkMapping->expects(self::any())->method('getName')->willReturn('name');
        $linkMapping->expects(self::any())->method('getGroups')->willReturn($groups);
        $linkMapping->expects(self::any())->method('getLinkNormalizer')->willReturn($this->getLinkNormalizer($nullLink));

        return $linkMapping;
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
            Request $request,
            $object,
            NormalizerContextInterface $context,
            NormalizerInterface $normalizer = null
        ) {
            return $object->getName();
        });

        return $fieldNormalizer;
    }

    /**
     * @param bool $nullLink
     *
     * @return LinkNormalizerInterface
     */
    private function getLinkNormalizer(bool $nullLink): LinkNormalizerInterface
    {
        /** @var LinkNormalizerInterface|\PHPUnit_Framework_MockObject_MockObject $linkNormalizer */
        $linkNormalizer = $this->getMockBuilder(LinkNormalizerInterface::class)
            ->setMethods([])
            ->getMockForAbstractClass();

        $linkNormalizer->expects(self::any())->method('normalizeLink')->willReturnCallback(function (
            string $path,
            Request $request,
            $object,
            NormalizerContextInterface $context
        ) use ($nullLink) {
            if ($nullLink) {
                return null;
            }

            return ['href' => '/api/model/'.$object->getId()];
        });

        return $linkNormalizer;
    }

    /**
     * @param array $groups
     *
     * @return NormalizerContextInterface
     */
    private function getNormalizerContext(array $groups = []): NormalizerContextInterface
    {
        /** @var NormalizerContextInterface|\PHPUnit_Framework_MockObject_MockObject $context */
        $context = $this->getMockBuilder(NormalizerContextInterface::class)
            ->setMethods([])
            ->getMockForAbstractClass();

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

    /**
     * @return Request
     */
    private function getRequest(): Request
    {
        /** @var Request|\PHPUnit_Framework_MockObject_MockObject $request */
        $request = $this->getMockBuilder(Request::class)->getMockForAbstractClass();

        return $request;
    }
}
