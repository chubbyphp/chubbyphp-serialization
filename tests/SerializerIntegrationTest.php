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
use Psr\Log\AbstractLogger;

/**
 * @coversNothing
 */
class SerializerIntegrationTest extends TestCase
{
    public function testSerialize()
    {
        $logger = $this->getLogger();

        $serializer = new Serializer(
            new Normalizer(
                new NormalizerObjectMappingRegistry([
                    new ManyModelMapping(),
                    new ModelMapping(),
                    new OneModelMapping(),
                ]),
                $logger
            ),
            new Encoder([new JsonTypeEncoder(true)])
        );

        $model = new Model();
        $model->setName('Name');
        $model->setOne((new OneModel())->setName('Name')->setValue('Value'));
        $model->setManies([(new ManyModel())->setName('Name')->setValue('Value')]);

        $expectedJson = <<<EOD
{
    "id": "ebac0dd9-8eca-4eb9-9fac-aeef65c5c59a",
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
    "_links": {
        "self": {
            "href": "/api/model/ebac0dd9-8eca-4eb9-9fac-aeef65c5c59a",
            "templated": false,
            "rel": [],
            "attributes": {
                "method": "GET"
            }
        }
    },
    "_type": "model"
}
EOD;

        self::assertSame($expectedJson, $serializer->serialize($model, 'application/json'));

        self::assertEquals(
            [
                [
                    'level' => 'info',
                    'message' => 'serialize: path {path}',
                    'context' => [
                        'path' => 'id',
                    ],
                ],
                [
                    'level' => 'info',
                    'message' => 'serialize: path {path}',
                    'context' => [
                        'path' => 'name',
                    ],
                ],
                [
                    'level' => 'info',
                    'message' => 'serialize: path {path}',
                    'context' => [
                        'path' => 'one',
                    ],
                ],
                [
                    'level' => 'info',
                    'message' => 'serialize: path {path}',
                    'context' => [
                        'path' => 'one.name',
                    ],
                ],
                [
                    'level' => 'info',
                    'message' => 'serialize: path {path}',
                    'context' => [
                        'path' => 'one.value',
                    ],
                ],
                [
                    'level' => 'info',
                    'message' => 'serialize: path {path}',
                    'context' => [
                        'path' => 'manies',
                    ],
                ],
                [
                    'level' => 'info',
                    'message' => 'serialize: path {path}',
                    'context' => [
                        'path' => 'manies[0].name',
                    ],
                ],
                [
                    'level' => 'info',
                    'message' => 'serialize: path {path}',
                    'context' => [
                        'path' => 'manies[0].value',
                    ],
                ],
            ],
            $logger->getEntries()
        );
    }

    public function testSerializeWithGroup()
    {
        $logger = $this->getLogger();

        $serializer = new Serializer(
            new Normalizer(
                new NormalizerObjectMappingRegistry([
                    new ManyModelMapping(),
                    new ModelMapping(),
                    new OneModelMapping(),
                ]),
                $logger
            ),
            new Encoder([new JsonTypeEncoder(true)])
        );

        $model = new Model();
        $model->setName('Name');
        $model->setOne((new OneModel())->setName('Name')->setValue('Value'));
        $model->setManies([(new ManyModel())->setName('Name')->setValue('Value')]);

        $expectedJson = <<<EOD
{
    "id": "ebac0dd9-8eca-4eb9-9fac-aeef65c5c59a",
    "name": "Name",
    "_type": "model"
}
EOD;

        $context = NormalizerContextBuilder::create()->setGroups(['baseInformation'])->getContext();

        self::assertSame(
            $expectedJson,
            $serializer->serialize($model, 'application/json', $context)
        );

        self::assertEquals(
            [
                [
                    'level' => 'info',
                    'message' => 'serialize: path {path}',
                    'context' => [
                        'path' => 'id',
                    ],
                ],
                [
                    'level' => 'info',
                    'message' => 'serialize: path {path}',
                    'context' => [
                        'path' => 'name',
                    ],
                ],
            ],
            $logger->getEntries()
        );
    }

    public function testSerializeWithoutObject()
    {
        $this->expectException(SerializerLogicException::class);
        $this->expectExceptionMessage('Wrong data type "" at path : "string"');

        $logger = $this->getLogger();

        $serializer = new Serializer(
            new Normalizer(
                new NormalizerObjectMappingRegistry([
                    new ManyModelMapping(),
                    new ModelMapping(),
                ]),
                $logger
            ),
            new Encoder([new JsonTypeEncoder(true)])
        );

        $serializer->serialize('test', 'application/json');
    }

    public function testSerializeWithoutObjectMapping()
    {
        $this->expectException(SerializerLogicException::class);
        $this->expectExceptionMessage('There is no mapping for class: "stdClass"');

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

    /**
     * @return AbstractLogger
     */
    private function getLogger()
    {
        return new class() extends AbstractLogger {
            /**
             * @var array
             */
            private $entries = [];

            public function log($level, $message, array $context = [])
            {
                $this->entries[] = ['level' => $level, 'message' => $message, 'context' => $context];
            }

            /**
             * @return array
             */
            public function getEntries(): array
            {
                return $this->entries;
            }
        };
    }
}
