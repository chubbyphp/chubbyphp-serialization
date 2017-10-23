<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization;

use Chubbyphp\Serialization\Encoder\Encoder;
use Chubbyphp\Serialization\Encoder\JsonTypeEncoder;
use Chubbyphp\Serialization\Normalizer\Normalizer;
use Chubbyphp\Serialization\Normalizer\NormalizerContextBuilder;
use Chubbyphp\Serialization\Normalizer\NormalizerObjectMappingRegistry;
use Chubbyphp\Serialization\Serializer;
use Chubbyphp\Serialization\SerializerLogicException;
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
    public function testSerialize()
    {
        $serializer = new Serializer(
            new Normalizer(
                new NormalizerObjectMappingRegistry([
                    new ChildModelMapping(),
                    new ParentModelMapping(),
                ])
            ),
            new Encoder([new JsonTypeEncoder(true)])
        );

        $parentModel = new ParentModel();
        $parentModel->setName('Name');
        $parentModel->setChildren([(new ChildModel())->setName('Name')->setValue('Value')]);

        $expectedJson = <<<EOD
{
    "name": "Name",
    "children": [
        {
            "name": "Name",
            "value": "Value",
            "_type": "child-model"
        }
    ],
    "_embedded": {
        "relatedChildren": [
            {
                "name": "Name",
                "value": "Value",
                "_type": "child-model"
            }
        ]
    },
    "_type": "parent-model"
}
EOD;

        self::assertSame($expectedJson, $serializer->serialize($parentModel, 'application/json'));
    }

    public function testSerializeWithGroup()
    {
        $serializer = new Serializer(
            new Normalizer(
                new NormalizerObjectMappingRegistry([
                    new ChildModelMapping(),
                    new ParentModelMapping(),
                ])
            ),
            new Encoder([new JsonTypeEncoder(true)])
        );

        $parentModel = new ParentModel();
        $parentModel->setName('Name');
        $parentModel->setChildren([(new ChildModel())->setName('Name')->setValue('Value')]);

        $expectedJson = <<<EOD
{
    "_embedded": {
        "relatedChildren": [
            {
                "name": "Name",
                "value": "Value",
                "_type": "child-model"
            }
        ]
    },
    "_type": "parent-model"
}
EOD;

        $context = NormalizerContextBuilder::create()->setGroups(['related'])->getContext();

        self::assertSame(
            $expectedJson,
            $serializer->serialize($parentModel, 'application/json', $context)
        );
    }

    public function testSerializeWithoutObject()
    {
        self::expectException(SerializerLogicException::class);
        self::expectExceptionMessage('Wrong data type "" at path : "string"');

        $serializer = new Serializer(
            new Normalizer(
                new NormalizerObjectMappingRegistry([
                    new ChildModelMapping(),
                    new ParentModelMapping(),
                ])
            ),
            new Encoder([new JsonTypeEncoder(true)])
        );

        $serializer->serialize('test', 'application/json');
    }

    public function testSerializeWithoutObjectMapping()
    {
        self::expectException(SerializerLogicException::class);
        self::expectExceptionMessage('There is no mapping for class: "stdClass"');

        $serializer = new Serializer(
            new Normalizer(
                new NormalizerObjectMappingRegistry([
                    new ChildModelMapping(),
                    new ParentModelMapping(),
                ])
            ),
            new Encoder([new JsonTypeEncoder(true)])
        );

        $serializer->serialize(new \stdClass(), 'application/json');
    }
}
