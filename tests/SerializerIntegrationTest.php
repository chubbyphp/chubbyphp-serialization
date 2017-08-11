<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization;

use Chubbyphp\Serialization\Registry\ObjectMappingRegistry;
use Chubbyphp\Serialization\Serializer;
use Chubbyphp\Tests\Serialization\Resources\Item;
use Chubbyphp\Tests\Serialization\Resources\ItemMapping;
use Chubbyphp\Tests\Serialization\Resources\Search;
use Chubbyphp\Tests\Serialization\Resources\SearchMapping;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * @coversNothing
 * @group integration
 */
class SerializerIntegrationTest extends \PHPUnit_Framework_TestCase
{
    public function testSerialize()
    {
        $registry = new ObjectMappingRegistry([
            new SearchMapping(),
            new ItemMapping(),
        ]);

        $serializer = new Serializer($registry);

        $request = $this->getRequest();

        $search = new Search();
        $search
            ->setPage(1)
            ->setPerPage(10)
            ->setSort('name')
            ->setOrder('asc')
            ->setItems([
                (new Item('id1'))
                    ->setName('A fancy Name')
                    ->setTreeValues([1 => [2 => '3']])
                    ->setProgress(76.8)->setActive(true),
                (new Item('id2'))
                    ->setName('B fancy Name')
                    ->setTreeValues([1 => [2 => '3', 3 => '4']])
                    ->setProgress(24.7)
                    ->setActive(true),
                (new Item('id3'))
                    ->setName('C fancy Name')
                    ->setTreeValues([1 => [2 => '3', 3 => '4', 6 => '7']])
                    ->setProgress(100)
                    ->setActive(false),
            ]);

        $data = $serializer->serialize($request, $search);

        self::assertEquals([
            'page' => 1,
            'perPage' => 10,
            'search' => null,
            'sort' => 'name',
            'order' => 'asc',
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
                    'href' => 'http://test.com/items/?page=1&perPage=10&sort=name&order=asc',
                    'method' => 'GET',
                ],
                'create' => [
                    'href' => 'http://test.com/items/',
                    'method' => 'POST',
                ],
            ],
            '_type' => 'search',
        ], $data);
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
