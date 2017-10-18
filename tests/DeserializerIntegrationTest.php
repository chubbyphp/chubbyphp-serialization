<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization;

use Chubbyphp\Serialization\Encoder\Encoder;
use Chubbyphp\Serialization\Encoder\JsonTypeEncoder;
use Chubbyphp\Serialization\Normalizer\Normalizer;
use Chubbyphp\Serialization\Normalizer\NormalizerContextBuilder;
use Chubbyphp\Serialization\Normalizer\NormalizerObjectMappingRegistry;
use Chubbyphp\Serialization\Serializer;
use Chubbyphp\Serialization\SerializerRuntimeException;
use Chubbyphp\Tests\Serialization\Resources\Mapping\BaseChildModelMapping;
use Chubbyphp\Tests\Serialization\Resources\Mapping\ChildModelMapping;
use Chubbyphp\Tests\Serialization\Resources\Mapping\ParentModelMapping;
use Chubbyphp\Tests\Serialization\Resources\Model\ChildModel;
use Chubbyphp\Tests\Serialization\Resources\Model\ParentModel;
use PHPUnit\Framework\TestCase;

/**
 * @coversNothing
 */
class SerializerIntegrationTest extends TestCase
{
    public function testDenormalizeByClass()
    {
        $childModelMapping = new ChildModelMapping();

        $serializer = new Serializer(
            new Encoder([new JsonTypeEncoder()]),
            new Normalizer(
                new NormalizerObjectMappingRegistry([
                    new BaseChildModelMapping($childModelMapping, ['child-model']),
                    $childModelMapping,
                    new ParentModelMapping(),
                ])
            )
        );

        $data = json_encode([
            'name' => 'Name',
            'children' => [
                [
                    '_type' => 'child-model',
                    'name' => 'Name',
                    'value' => 'Value',
                ],
            ],
        ]);

        $parentObject = $serializer->serialize(ParentModel::class, $data, 'application/json');

        self::assertSame('Name', $parentObject->getName());
        self::assertCount(1, $parentObject->getChildren());
        self::assertSame('Name', $parentObject->getChildren()[0]->getName());
        self::assertSame('Value', $parentObject->getChildren()[0]->getValue());
    }

    public function testDenormalizeByClassAndMissingChildType()
    {
        self::expectException(SerializerRuntimeException::class);
        self::expectExceptionMessage('Missing object type, supported are "child-model" at path: "children[0]"');

        $childModelMapping = new ChildModelMapping();

        $serializer = new Serializer(
            new Encoder([new JsonTypeEncoder()]),
            new Normalizer(
                new NormalizerObjectMappingRegistry([
                    new BaseChildModelMapping($childModelMapping, ['child-model']),
                    $childModelMapping,
                    new ParentModelMapping(),
                ])
            )
        );

        $data = json_encode([
            'name' => 'Name',
            'children' => [
                [
                    'name' => 'Name',
                    'value' => 'Value',
                ],
            ],
        ]);

        $serializer->serialize(ParentModel::class, $data, 'application/json');
    }

    public function testDenormalizeByClassAndInvalidChildType()
    {
        self::expectException(SerializerRuntimeException::class);
        self::expectExceptionMessage('Unsupported object type "unknown", supported are "child-model" at path: "children[0]"');

        $childModelMapping = new ChildModelMapping();

        $serializer = new Serializer(
            new Encoder([new JsonTypeEncoder()]),
            new Normalizer(
                new NormalizerObjectMappingRegistry([
                    new BaseChildModelMapping($childModelMapping, ['child-model']),
                    $childModelMapping,
                    new ParentModelMapping(),
                ])
            )
        );

        $data = json_encode([
            'name' => 'Name',
            'children' => [
                [
                    '_type' => 'unknown',
                    'name' => 'Name',
                    'value' => 'Value',
                ],
            ],
        ]);

        $serializer->serialize(ParentModel::class, $data, 'application/json');
    }

    public function testDenormalizeByObject()
    {
        $childModelMapping = new ChildModelMapping();

        $serializer = new Serializer(
            new Encoder([new JsonTypeEncoder()]),
            new Normalizer(
                new NormalizerObjectMappingRegistry([
                    new BaseChildModelMapping($childModelMapping, ['child-model']),
                    $childModelMapping,
                    new ParentModelMapping(),
                ])
            )
        );

        $data = json_encode([
            'name' => 'Name',
            'children' => [
                [
                    '_type' => 'child-model',
                    'name' => 'Name',
                    'value' => 'Value',
                ],
            ],
        ]);

        $childrenObject1 = new ChildModel();
        $childrenObject1->setName('oldName1');

        $childrenObject2 = new ChildModel();
        $childrenObject2->setName('oldNam2');

        $parentObject = new ParentModel();
        $parentObject->setName('oldName');
        $parentObject->setChildren([$childrenObject1, $childrenObject2]);

        $parentObject = $serializer->serialize($parentObject, $data, 'application/json');

        self::assertSame('Name', $parentObject->getName());
        self::assertCount(1, $parentObject->getChildren());
        self::assertSame('Name', $parentObject->getChildren()[0]->getName());
        self::assertSame('Value', $parentObject->getChildren()[0]->getValue());
    }

    public function testDenormalizeWithAdditionalFieldsExpectsException()
    {
        self::expectException(SerializerRuntimeException::class);
        self::expectExceptionMessage('There are additional field(s) at paths: "unknownField"');

        $childModelMapping = new ChildModelMapping();

        $serializer = new Serializer(
            new Encoder([new JsonTypeEncoder()]),
            new Normalizer(
                new NormalizerObjectMappingRegistry([
                    new BaseChildModelMapping($childModelMapping, ['child-model']),
                    $childModelMapping,
                    new ParentModelMapping(),
                ])
            )
        );

        $data = json_encode(['name' => 'Name', 'unknownField' => 'value']);

        $serializer->serialize(ParentModel::class, $data, 'application/json');
    }

    public function testDenormalizeWithAllowedAdditionalFields()
    {
        $childModelMapping = new ChildModelMapping();

        $serializer = new Serializer(
            new Encoder([new JsonTypeEncoder()]),
            new Normalizer(
                new NormalizerObjectMappingRegistry([
                    new BaseChildModelMapping($childModelMapping, ['child-model']),
                    $childModelMapping,
                    new ParentModelMapping(),
                ])
            )
        );

        $data = json_encode(['name' => 'Name', 'unknownField' => 'value']);

        $object = $serializer->serialize(
            ParentModel::class,
            $data,
            'application/json',
            NormalizerContextBuilder::create()->setAllowedAdditionalFields(true)->getContext()
        );

        self::assertSame('Name', $object->getName());
    }
}
