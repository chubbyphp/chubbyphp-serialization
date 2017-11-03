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
use Chubbyphp\Tests\Serialization\Resources\Mapping\ManyModelMapping;
use Chubbyphp\Tests\Serialization\Resources\Mapping\ModelMapping;
use Chubbyphp\Tests\Serialization\Resources\Mapping\OneModelMapping;
use Chubbyphp\Tests\Serialization\Resources\Model\ManyModel;
use Chubbyphp\Tests\Serialization\Resources\Model\Model;
use Chubbyphp\Tests\Serialization\Resources\Model\OneModel;
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
                    new ManyModelMapping(),
                    new ModelMapping(),
                    new OneModelMapping(),
                ])
            ),
            new Encoder([new JsonTypeEncoder(true)])
        );

        $model = new Model();
        $model->setName('Name');
        $model->setOne((new OneModel())->setName('Name')->setValue('Value'));
        $model->setManies([(new ManyModel())->setName('Name')->setValue('Value')]);

        $expectedJson = <<<EOD
{
    "name": "Name",
    "one": {
        "name": "Name",
        "value": "Value",
        "_type": "one-model"
    },
    "manies": [
        {
            "name": "Name",
            "value": "Value",
            "_type": "many-model"
        }
    ],
    "_type": "model"
}
EOD;

        self::assertSame($expectedJson, $serializer->serialize($model, 'application/json'));
    }

    public function testSerializeWithGroup()
    {
        $serializer = new Serializer(
            new Normalizer(
                new NormalizerObjectMappingRegistry([
                    new ManyModelMapping(),
                    new ModelMapping(),
                    new OneModelMapping(),
                ])
            ),
            new Encoder([new JsonTypeEncoder(true)])
        );

        $model = new Model();
        $model->setName('Name');
        $model->setOne((new OneModel())->setName('Name')->setValue('Value'));
        $model->setManies([(new ManyModel())->setName('Name')->setValue('Value')]);

        $expectedJson = <<<EOD
{
    "name": "Name",
    "_type": "model"
}
EOD;

        $context = NormalizerContextBuilder::create()->setGroups(['baseInformation'])->getContext();

        self::assertSame(
            $expectedJson,
            $serializer->serialize($model, 'application/json', $context)
        );
    }

    public function testSerializeWithoutObject()
    {
        self::expectException(SerializerLogicException::class);
        self::expectExceptionMessage('Wrong data type "" at path : "string"');

        $serializer = new Serializer(
            new Normalizer(
                new NormalizerObjectMappingRegistry([
                    new ManyModelMapping(),
                    new ModelMapping(),
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
                    new ManyModelMapping(),
                    new ModelMapping(),
                ])
            ),
            new Encoder([new JsonTypeEncoder(true)])
        );

        $serializer->serialize(new \stdClass(), 'application/json');
    }
}
