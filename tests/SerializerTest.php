<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization;

use Chubbyphp\Serialization\Link\LinkInterface;
use Chubbyphp\Serialization\Mapping\FieldMappingInterface;
use Chubbyphp\Serialization\Mapping\LinkMappingInterface;
use Chubbyphp\Serialization\Mapping\ObjectMappingInterface;
use Chubbyphp\Serialization\NotObjectException;
use Chubbyphp\Serialization\Registry\ObjectMappingRegistryInterface;
use Chubbyphp\Serialization\Serializer;
use Chubbyphp\Serialization\Serializer\Field\FieldSerializerInterface;
use Chubbyphp\Serialization\Serializer\Link\LinkSerializerInterface;
use Chubbyphp\Serialization\SerializerInterface;
use Chubbyphp\Tests\Serialization\Resources\Item;
use Chubbyphp\Tests\Serialization\Resources\Search;
use Psr\Log\LoggerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * @covers \Chubbyphp\Serialization\Serializer
 */
class SerializerTest extends \PHPUnit_Framework_TestCase
{
    public function testSerialize()
    {
        $objectMappingRegistry = $this->getObjectMappingRegistry([
            Item::class => $this->getObjectMapping(
                Item::class,
                'item',
                [
                    $this->getFieldMapping('id', $this->getValueFieldSerializer('id')),
                    $this->getFieldMapping('name', $this->getValueFieldSerializer('name')),
                    $this->getFieldMapping('treeValues', $this->getValueFieldSerializer('treeValues')),
                    $this->getFieldMapping('progress', $this->getValueFieldSerializer('progress')),
                    $this->getFieldMapping('active', $this->getValueFieldSerializer('active')),
                ],
                [],
                [
                    $this->getLinkMapping('read', $this->getLinkSerializer('http://test.com/items/', 'GET')),
                    $this->getLinkMapping('update', $this->getLinkSerializer('http://test.com/items/', 'PUT')),
                    $this->getLinkMapping('delete', $this->getLinkSerializer('http://test.com/items/', 'DELETE')),
                ]
            ),
            Search::class => $this->getObjectMapping(
                Search::class,
                'search',
                [
                    $this->getFieldMapping('page', $this->getValueFieldSerializer('page')),
                    $this->getFieldMapping('perPage', $this->getValueFieldSerializer('perPage')),
                    $this->getFieldMapping('search', $this->getValueFieldSerializer('search')),
                    $this->getFieldMapping('sort', $this->getValueFieldSerializer('sort')),
                    $this->getFieldMapping('order', $this->getValueFieldSerializer('order')),
                ],
                [
                    $this->getFieldMapping('mainItem', $this->getObjectFieldSerializer('mainItem')),
                    $this->getFieldMapping('items', $this->getCollectionFieldSerializer('items')),
                ],
                [
                    $this->getLinkMapping('self', $this->getLinkSerializer('http://test.com/items/', 'GET')),
                    $this->getLinkMapping('create', $this->getLinkSerializer('http://test.com/items/', 'POST')),
                ]
            ),
        ]);

        $logger = $this->getLogger();

        $serializer = new Serializer($objectMappingRegistry, $logger);

        $search = new Search();
        $search
            ->setPage(1)
            ->setPerPage(10)
            ->setSort('name')
            ->setOrder('asc')
            ->setItems([
                (new Item('id1'))
                    ->setName('A fancy Name')
                    ->setTreeValues([1 => [2 => 3]])
                    ->setProgress(76.8)->setActive(true),
                (new Item('id2'))
                    ->setName('B fancy Name')
                    ->setTreeValues([1 => [2 => 3, 3 => 4]])
                    ->setProgress(24.7)
                    ->setActive(true),
                (new Item('id3'))
                    ->setName('C fancy Name')
                    ->setTreeValues([1 => [2 => 3, 3 => 4, 6 => 7]])
                    ->setProgress(100)
                    ->setActive(false),
            ]);

        $data = $serializer->serialize($this->getRequest(), $search);

        self::assertEquals([
            'page' => 1,
            'perPage' => 10,
            'search' => null,
            'sort' => 'name',
            'order' => 'asc',
            '_type' => 'search',
            '_embedded' => [
                'mainItem' => [
                    'id' => 'id1',
                    'name' => 'A fancy Name',
                    'treeValues' => [
                        1 => [
                            2 => 3,
                        ],
                    ],
                    'progress' => 76.8,
                    'active' => true,
                    '_type' => 'item',
                    '_links' => [
                        'read' => [
                            'href' => 'http://test.com/items/id1',
                            'method' => 'GET',
                        ],
                        'update' => [
                            'href' => 'http://test.com/items/id1',
                            'method' => 'PUT',
                        ],
                        'delete' => [
                            'href' => 'http://test.com/items/id1',
                            'method' => 'DELETE',
                        ],
                    ],
                ],
                'items' => [
                    [
                        'id' => 'id1',
                        'name' => 'A fancy Name',
                        'treeValues' => [
                            1 => [
                                2 => 3,
                            ],
                        ],
                        'progress' => 76.8,
                        'active' => true,
                        '_type' => 'item',
                        '_links' => [
                            'read' => [
                                'href' => 'http://test.com/items/id1',
                                'method' => 'GET',
                            ],
                            'update' => [
                                'href' => 'http://test.com/items/id1',
                                'method' => 'PUT',
                            ],
                            'delete' => [
                                'href' => 'http://test.com/items/id1',
                                'method' => 'DELETE',
                            ],
                        ],
                    ],
                    [
                        'id' => 'id2',
                        'name' => 'B fancy Name',
                        'treeValues' => [
                            1 => [
                                2 => 3,
                                3 => 4,
                            ],
                        ],
                        'progress' => 24.7,
                        'active' => true,
                        '_type' => 'item',
                        '_links' => [
                            'read' => [
                                'href' => 'http://test.com/items/id2',
                                'method' => 'GET',
                            ],
                            'update' => [
                                'href' => 'http://test.com/items/id2',
                                'method' => 'PUT',
                            ],
                            'delete' => [
                                'href' => 'http://test.com/items/id2',
                                'method' => 'DELETE',
                            ],
                        ],
                    ],
                    [
                        'id' => 'id3',
                        'name' => 'C fancy Name',
                        'treeValues' => [
                            1 => [
                                2 => 3,
                                3 => 4,
                                6 => 7,
                            ],
                        ],
                        'progress' => 100.0,
                        'active' => false,
                        '_type' => 'item',
                        '_links' => [
                            'read' => [
                                'href' => 'http://test.com/items/id3',
                                'method' => 'GET',
                            ],
                            'update' => [
                                'href' => 'http://test.com/items/id3',
                                'method' => 'PUT',
                            ],
                            'delete' => [
                                'href' => 'http://test.com/items/id3',
                                'method' => 'DELETE',
                            ],
                        ],
                    ],
                ],
            ],
            '_links' => [
                'self' => [
                    'href' => 'http://test.com/items/',
                    'method' => 'GET',
                ],
                'create' => [
                    'href' => 'http://test.com/items/',
                    'method' => 'POST',
                ],
            ],
        ], $data);

        self::assertEquals([
            [
                'level' => 'info',
                'message' => 'deserialize: path {path}',
                'context' => [
                    'path' => 'page',
                ],
            ],
            [
                'level' => 'info',
                'message' => 'deserialize: path {path}',
                'context' => [
                    'path' => 'perPage',
                ],
            ],
            [
                'level' => 'info',
                'message' => 'deserialize: path {path}',
                'context' => [
                    'path' => 'search',
                ],
            ],
            [
                'level' => 'info',
                'message' => 'deserialize: path {path}',
                'context' => [
                    'path' => 'sort',
                ],
            ],
            [
                'level' => 'info',
                'message' => 'deserialize: path {path}',
                'context' => [
                    'path' => 'order',
                ],
            ],
            [
                'level' => 'info',
                'message' => 'deserialize: path {path}',
                'context' => [
                    'path' => 'mainItem',
                ],
            ],
            [
                'level' => 'info',
                'message' => 'deserialize: path {path}',
                'context' => [
                    'path' => 'mainItem.id',
                ],
            ],
            [
                'level' => 'info',
                'message' => 'deserialize: path {path}',
                'context' => [
                    'path' => 'mainItem.name',
                ],
            ],
            [
                'level' => 'info',
                'message' => 'deserialize: path {path}',
                'context' => [
                    'path' => 'mainItem.treeValues',
                ],
            ],
            [
                'level' => 'info',
                'message' => 'deserialize: path {path}',
                'context' => [
                    'path' => 'mainItem.progress',
                ],
            ],
            [
                'level' => 'info',
                'message' => 'deserialize: path {path}',
                'context' => [
                    'path' => 'mainItem.active',
                ],
            ],
            [
                'level' => 'info',
                'message' => 'deserialize: path {path}',
                'context' => [
                    'path' => 'mainItem._links.read',
                ],
            ],
            [
                'level' => 'info',
                'message' => 'deserialize: path {path}',
                'context' => [
                    'path' => 'mainItem._links.update',
                ],
            ],
            [
                'level' => 'info',
                'message' => 'deserialize: path {path}',
                'context' => [
                    'path' => 'mainItem._links.delete',
                ],
            ],
            [
                'level' => 'info',
                'message' => 'deserialize: path {path}',
                'context' => [
                    'path' => 'items',
                ],
            ],
            [
                'level' => 'info',
                'message' => 'deserialize: path {path}',
                'context' => [
                    'path' => 'items[0].id',
                ],
            ],
            [
                'level' => 'info',
                'message' => 'deserialize: path {path}',
                'context' => [
                    'path' => 'items[0].name',
                ],
            ],
            [
                'level' => 'info',
                'message' => 'deserialize: path {path}',
                'context' => [
                    'path' => 'items[0].treeValues',
                ],
            ],
            [
                'level' => 'info',
                'message' => 'deserialize: path {path}',
                'context' => [
                    'path' => 'items[0].progress',
                ],
            ],
            [
                'level' => 'info',
                'message' => 'deserialize: path {path}',
                'context' => [
                    'path' => 'items[0].active',
                ],
            ],
            [
                'level' => 'info',
                'message' => 'deserialize: path {path}',
                'context' => [
                    'path' => 'items[0]._links.read',
                ],
            ],
            [
                'level' => 'info',
                'message' => 'deserialize: path {path}',
                'context' => [
                    'path' => 'items[0]._links.update',
                ],
            ],
            [
                'level' => 'info',
                'message' => 'deserialize: path {path}',
                'context' => [
                    'path' => 'items[0]._links.delete',
                ],
            ],
            [
                'level' => 'info',
                'message' => 'deserialize: path {path}',
                'context' => [
                    'path' => 'items[1].id',
                ],
            ],
            [
                'level' => 'info',
                'message' => 'deserialize: path {path}',
                'context' => [
                    'path' => 'items[1].name',
                ],
            ],
            [
                'level' => 'info',
                'message' => 'deserialize: path {path}',
                'context' => [
                    'path' => 'items[1].treeValues',
                ],
            ],
            [
                'level' => 'info',
                'message' => 'deserialize: path {path}',
                'context' => [
                    'path' => 'items[1].progress',
                ],
            ],
            [
                'level' => 'info',
                'message' => 'deserialize: path {path}',
                'context' => [
                    'path' => 'items[1].active',
                ],
            ],
            [
                'level' => 'info',
                'message' => 'deserialize: path {path}',
                'context' => [
                    'path' => 'items[1]._links.read',
                ],
            ],
            [
                'level' => 'info',
                'message' => 'deserialize: path {path}',
                'context' => [
                    'path' => 'items[1]._links.update',
                ],
            ],
            [
                'level' => 'info',
                'message' => 'deserialize: path {path}',
                'context' => [
                    'path' => 'items[1]._links.delete',
                ],
            ],
            [
                'level' => 'info',
                'message' => 'deserialize: path {path}',
                'context' => [
                    'path' => 'items[2].id',
                ],
            ],
            [
                'level' => 'info',
                'message' => 'deserialize: path {path}',
                'context' => [
                    'path' => 'items[2].name',
                ],
            ],
            [
                'level' => 'info',
                'message' => 'deserialize: path {path}',
                'context' => [
                    'path' => 'items[2].treeValues',
                ],
            ],
            [
                'level' => 'info',
                'message' => 'deserialize: path {path}',
                'context' => [
                    'path' => 'items[2].progress',
                ],
            ],
            [
                'level' => 'info',
                'message' => 'deserialize: path {path}',
                'context' => [
                    'path' => 'items[2].active',
                ],
            ],
            [
                'level' => 'info',
                'message' => 'deserialize: path {path}',
                'context' => [
                    'path' => 'items[2]._links.read',
                ],
            ],
            [
                'level' => 'info',
                'message' => 'deserialize: path {path}',
                'context' => [
                    'path' => 'items[2]._links.update',
                ],
            ],
            [
                'level' => 'info',
                'message' => 'deserialize: path {path}',
                'context' => [
                    'path' => 'items[2]._links.delete',
                ],
            ],
            [
                'level' => 'info',
                'message' => 'deserialize: path {path}',
                'context' => [
                    'path' => '_links.self',
                ],
            ],
            [
                'level' => 'info',
                'message' => 'deserialize: path {path}',
                'context' => [
                    'path' => '_links.create',
                ],
            ],
        ], $logger->__logs);
    }

    public function testSerializeWithInvalidType()
    {
        self::expectException(NotObjectException::class);
        self::expectExceptionMessage('Input is not an object, type string given');

        $objectMappingRegistry = $this->getObjectMappingRegistry([]);

        $logger = $this->getLogger();

        $serializer = new Serializer($objectMappingRegistry, $logger);

        $serializer->serialize($this->getRequest(), 'test');
    }

    /**
     * @return ObjectMappingRegistryInterface
     */
    private function getObjectMappingRegistry(array $mappings): ObjectMappingRegistryInterface
    {
        /** @var ObjectMappingRegistryInterface|\PHPUnit_Framework_MockObject_MockObject $registry */
        $registry = $this
            ->getMockBuilder(ObjectMappingRegistryInterface::class)
            ->setMethods(['getObjectMappingForClass'])
            ->getMockForAbstractClass();

        $registry->expects(self::any())->method('getObjectMappingForClass')->willReturnCallback(
            function (string $class) use ($mappings) {
                if (isset($mappings[$class])) {
                    return $mappings[$class];
                }

                throw new \InvalidArgumentException(sprintf('There is no mapping for class: %s', $class));
            }
        );

        return $registry;
    }

    /**
     * @return ObjectMappingInterface
     */
    private function getObjectMapping(
        string $class,
        string $type,
        array $fieldMappings = [],
        array $embeddedFieldMappings = [],
        array $linkMappings = []
    ): ObjectMappingInterface {
        /** @var ObjectMappingInterface|\PHPUnit_Framework_MockObject_MockObject $objectMapping */
        $objectMapping = $this
            ->getMockBuilder(ObjectMappingInterface::class)
            ->setMethods([
                'getClass',
                'getType',
                'getFieldMappings'.
                'getEmbeddedFieldMappings',
                'getLinkMappings',
            ])
            ->getMockForAbstractClass();

        $objectMapping->expects(self::any())->method('getClass')->willReturnCallback(
            function () use ($class) {
                return $class;
            }
        );

        $objectMapping->expects(self::any())->method('getType')->willReturnCallback(
            function () use ($type) {
                return $type;
            }
        );

        $objectMapping->expects(self::any())->method('getFieldMappings')->willReturnCallback(
            function () use ($fieldMappings) {
                return $fieldMappings;
            }
        );

        $objectMapping->expects(self::any())->method('getEmbeddedFieldMappings')->willReturnCallback(
            function () use ($embeddedFieldMappings) {
                return $embeddedFieldMappings;
            }
        );

        $objectMapping->expects(self::any())->method('getLinkMappings')->willReturnCallback(
            function () use ($linkMappings) {
                return $linkMappings;
            }
        );

        return $objectMapping;
    }

    /**
     * @return FieldMappingInterface
     */
    private function getFieldMapping(string $name, FieldSerializerInterface $fieldSerializer): FieldMappingInterface
    {
        /** @var FieldMappingInterface|\PHPUnit_Framework_MockObject_MockObject $fieldMapping */
        $fieldMapping = $this
            ->getMockBuilder(FieldMappingInterface::class)
            ->setMethods(['getName', 'getFieldSerializer'])
            ->getMockForAbstractClass();

        $fieldMapping->expects(self::any())->method('getName')->willReturnCallback(
            function () use ($name) {
                return $name;
            }
        );

        $fieldMapping->expects(self::any())->method('getFieldSerializer')->willReturnCallback(
            function () use ($fieldSerializer) {
                return $fieldSerializer;
            }
        );

        return $fieldMapping;
    }

    /**
     * @return FieldSerializerInterface
     */
    private function getValueFieldSerializer(string $field): FieldSerializerInterface
    {
        /** @var FieldSerializerInterface|\PHPUnit_Framework_MockObject_MockObject $fieldSerializer */
        $fieldSerializer = $this
            ->getMockBuilder(FieldSerializerInterface::class)
            ->setMethods(['serializeField'])
            ->getMockForAbstractClass();

        $fieldSerializer->expects(self::any())->method('serializeField')->willReturnCallback(
            function (string $path, Request $request, $object, SerializerInterface $serializer = null) use ($field) {
                $method = 'get'.ucfirst($field);
                if (!method_exists($object, $method)) {
                    $method = 'is'.ucfirst($field);
                }

                return $object->$method();
            }
        );

        return $fieldSerializer;
    }

    /**
     * @return FieldSerializerInterface
     */
    private function getObjectFieldSerializer(string $field): FieldSerializerInterface
    {
        /** @var FieldSerializerInterface|\PHPUnit_Framework_MockObject_MockObject $fieldSerializer */
        $fieldSerializer = $this
            ->getMockBuilder(FieldSerializerInterface::class)
            ->setMethods(['serializeField'])
            ->getMockForAbstractClass();

        $fieldSerializer->expects(self::any())->method('serializeField')->willReturnCallback(
            function (string $path, Request $request, $object, SerializerInterface $serializer = null) use ($field) {
                $method = 'get'.ucfirst($field);

                return $serializer->serialize($request, $object->$method(), $path);
            }
        );

        return $fieldSerializer;
    }

    /**
     * @return FieldSerializerInterface
     */
    private function getCollectionFieldSerializer(string $field): FieldSerializerInterface
    {
        /** @var FieldSerializerInterface|\PHPUnit_Framework_MockObject_MockObject $fieldSerializer */
        $fieldSerializer = $this
            ->getMockBuilder(FieldSerializerInterface::class)
            ->setMethods(['serializeField'])
            ->getMockForAbstractClass();

        $fieldSerializer->expects(self::any())->method('serializeField')->willReturnCallback(
            function (string $path, Request $request, $object, SerializerInterface $serializer = null) use ($field) {
                $method = 'get'.ucfirst($field);

                $collection = [];
                foreach ($object->$method() as $i => $childObject) {
                    $subPath = $path.'['.$i.']';
                    $collection[$i] = $serializer->serialize($request, $childObject, $subPath);
                }

                return $collection;
            }
        );

        return $fieldSerializer;
    }

    /**
     * @return LinkMappingInterface
     */
    private function getLinkMapping(string $name, LinkSerializerInterface $linkSerializer): LinkMappingInterface
    {
        /** @var LinkMappingInterface|\PHPUnit_Framework_MockObject_MockObject $linkMapping */
        $linkMapping = $this
            ->getMockBuilder(LinkMappingInterface::class)
            ->setMethods(['getName', 'getLinkSerializer'])
            ->getMockForAbstractClass();

        $linkMapping->expects(self::any())->method('getName')->willReturnCallback(
            function () use ($name) {
                return $name;
            }
        );

        $linkMapping->expects(self::any())->method('getLinkSerializer')->willReturnCallback(
            function () use ($linkSerializer) {
                return $linkSerializer;
            }
        );

        return $linkMapping;
    }

    /**
     * @param string $href
     * @param string $method
     *
     * @return LinkSerializerInterface
     */
    private function getLinkSerializer(string $href, string $method): LinkSerializerInterface
    {
        /** @var LinkSerializerInterface|\PHPUnit_Framework_MockObject_MockObject $linkSerializer */
        $linkSerializer = $this
            ->getMockBuilder(LinkSerializerInterface::class)
            ->setMethods(['serializeLink'])
            ->getMockForAbstractClass();

        $linkSerializer->expects(self::any())->method('serializeLink')->willReturnCallback(
            function (string $path, Request $request, $object, array $fields) use ($href, $method) {
                if (method_exists($object, 'getId')) {
                    $href .= $object->getId();
                }

                return $this->getLink($href, $method);
            }
        );

        return $linkSerializer;
    }

    /**
     * @param string $href
     * @param string $method
     *
     * @return LinkInterface
     */
    private function getLink(string $href, string $method): LinkInterface
    {
        /** @var LinkInterface|\PHPUnit_Framework_MockObject_MockObject $link */
        $link = $this
            ->getMockBuilder(LinkInterface::class)
            ->setMethods(['jsonSerialize'])
            ->getMockForAbstractClass();

        $link->expects(self::any())->method('jsonSerialize')->willReturnCallback(
            function () use ($href, $method) {
                return [
                    'href' => $href,
                    'method' => $method,
                ];
            }
        );

        return $link;
    }

    /**
     * @return LoggerInterface
     */
    private function getLogger(): LoggerInterface
    {
        $methods = [
            'emergency',
            'alert',
            'critical',
            'error',
            'warning',
            'notice',
            'info',
            'debug',
        ];

        /** @var LoggerInterface|\PHPUnit_Framework_MockObject_MockObject $logger */
        $logger = $this
            ->getMockBuilder(LoggerInterface::class)
            ->setMethods(array_merge($methods, ['log']))
            ->getMockForAbstractClass();

        $logger->__logs = [];

        foreach ($methods as $method) {
            $logger
                ->expects(self::any())
                ->method($method)
                ->willReturnCallback(
                    function (string $message, array $context = []) use ($logger, $method) {
                        $logger->log($method, $message, $context);
                    }
                );
        }

        $logger
            ->expects(self::any())
            ->method('log')
            ->willReturnCallback(
                function (string $level, string $message, array $context = []) use ($logger) {
                    $logger->__logs[] = ['level' => $level, 'message' => $message, 'context' => $context];
                }
            );

        return $logger;
    }

    /**
     * @return Request
     */
    private function getRequest(): Request
    {
        return $this->getMockBuilder(Request::class)->getMockForAbstractClass();
    }
}
