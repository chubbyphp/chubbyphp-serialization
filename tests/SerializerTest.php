<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization;

use Chubbyphp\Serialization\Formatter\JsonFormatter;
use Chubbyphp\Serialization\Formatter\XmlFormatter;
use Chubbyphp\Serialization\Formatter\YamlFormatter;
use Chubbyphp\Serialization\Registry\ObjectMappingRegistry;
use Chubbyphp\Serialization\Serializer;
use Chubbyphp\Tests\Serialization\Resources\EmbeddedModel;
use Chubbyphp\Tests\Serialization\Resources\EmbeddedModelMapping;
use Chubbyphp\Tests\Serialization\Resources\Model;
use Chubbyphp\Tests\Serialization\Resources\ModelMapping;
use Psr\Http\Message\ServerRequestInterface as Request;

class SerializerTest extends \PHPUnit_Framework_TestCase
{
    public function testSerialize()
    {
        $registry = new ObjectMappingRegistry([
            new ModelMapping(),
            new EmbeddedModelMapping(),
        ]);

        $serializer = new Serializer($registry);

        $request = $this->getRequest();

        $model = new Model('id1');
        $model->setName('name1');
        $model->setEmbeddedModel((new EmbeddedModel())->setName('embedded1'));
        $model->setEmbeddedModels([
            (new EmbeddedModel())->setName('embedded2'),
            (new EmbeddedModel())->setName('embedded3'),
            (new EmbeddedModel())->setName('embedded4'),
        ]);

        $data = $serializer->serialize($request, $model);

        self::assertEquals([
            'name' => 'name1',
            '_embedded' => [
                'embeddedModel' => [
                    'name' => 'embedded1',
                ],
                'embeddedModels' => [
                    [
                        'name' => 'embedded2',
                    ],
                    [
                        'name' => 'embedded3',
                    ],
                                       [
                        'name' => 'embedded4',
                    ],
                ],
            ],
            '_links' => [
                'name:read' => [
                    'href' => 'http://test.com/models/id1',
                    'method' => 'GET',
                ],
                'name:update' => [
                    'href' => 'http://test.com/models/id1',
                    'method' => 'PUT',
                ],
                'name:delete' => [
                    'href' => 'http://test.com/models/id1',
                    'method' => 'DELETE',
                ],
            ],
        ], $data);

        $jsonFormatter = new JsonFormatter(JSON_PRETTY_PRINT);

        $json = $jsonFormatter->format($data);

        $expectedJson = <<<EOD
{
    "name": "name1",
    "_embedded": {
        "embeddedModel": {
            "name": "embedded1"
        },
        "embeddedModels": [
            {
                "name": "embedded2"
            },
            {
                "name": "embedded3"
            },
            {
                "name": "embedded4"
            }
        ]
    },
    "_links": {
        "name:read": {
            "href": "http:\/\/test.com\/models\/id1",
            "method": "GET"
        },
        "name:update": {
            "href": "http:\/\/test.com\/models\/id1",
            "method": "PUT"
        },
        "name:delete": {
            "href": "http:\/\/test.com\/models\/id1",
            "method": "DELETE"
        }
    }
}
EOD;
        self::assertEquals($expectedJson, $json);

        $yamlFormatter = new YamlFormatter(3);

        $yaml = $yamlFormatter->format($data);

        $expectedYaml = <<<EOD
name: name1
_embedded:
    embeddedModel:
        name: embedded1
    embeddedModels:
        - { name: embedded2 }
        - { name: embedded3 }
        - { name: embedded4 }
_links:
    'name:read':
        href: 'http://test.com/models/id1'
        method: GET
    'name:update':
        href: 'http://test.com/models/id1'
        method: PUT
    'name:delete':
        href: 'http://test.com/models/id1'
        method: DELETE

EOD;

        self::assertEquals($expectedYaml, $yaml);

        $xmlFormatter = new XmlFormatter(true);
        $xml = $xmlFormatter->format($data);

        $expectedXml = <<<EOD
<?xml version="1.0" encoding="UTF-8"?>
<name>name1</name>
<_embedded>
  <embeddedModel>
    <name>embedded1</name>
  </embeddedModel>
  <embeddedModels>
    <embeddedModel>
      <name>embedded2</name>
    </embeddedModel>
    <embeddedModel>
      <name>embedded3</name>
    </embeddedModel>
    <embeddedModel>
      <name>embedded4</name>
    </embeddedModel>
  </embeddedModels>
</_embedded>
<_links>
  <name:read>
    <href>http://test.com/models/id1</href>
    <method>GET</method>
  </name:read>
  <name:update>
    <href>http://test.com/models/id1</href>
    <method>PUT</method>
  </name:update>
  <name:delete>
    <href>http://test.com/models/id1</href>
    <method>DELETE</method>
  </name:delete>
</_links>

EOD;

        self::assertEquals($expectedXml, $xml);
    }

    /**
     * @return Request
     */
    private function getRequest(): Request
    {
        /** @var Request|\PHPUnit_Framework_MockObject_MockObject $request */
        $request = $this->getMockBuilder(Request::class)->setMethods([])->getMockForAbstractClass();

        return $request;
    }
}
