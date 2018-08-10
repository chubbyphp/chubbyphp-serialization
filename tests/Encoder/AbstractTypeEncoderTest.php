<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization\Encoder;

use PHPUnit\Framework\TestCase;

abstract class AbstractTypeEncoderTest extends TestCase
{
    /**
     * @dataProvider dataProvider
     *
     * @param array $data
     */
    abstract public function testFormat(array $data);

    /**
     * @return array
     */
    public function dataProvider(): array
    {
        return [
            [
                'data' => [
                    'page' => 1,
                    'perPage' => 10,
                    'search' => null,
                    'sort' => 'name',
                    'order' => 'asc',
                    '_embedded' => [
                        'mainItem' => [
                            'id' => 'id1',
                            'name' => 'A fäncy Name'.PHP_EOL,
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
                                    'templated' => false,
                                    'rels' => [],
                                    'attributes' => [
                                        'method' => 'GET',
                                    ],
                                ],
                                'update' => [
                                    'href' => 'http://test.com/items/id1',
                                    'templated' => false,
                                    'rels' => [],
                                    'attributes' => [
                                        'method' => 'PUT',
                                    ],
                                ],
                                'delete' => [
                                    'href' => 'http://test.com/items/id1',
                                    'templated' => false,
                                    'rels' => [],
                                    'attributes' => [
                                        'method' => 'DELETE',
                                    ],
                                ],
                            ],
                        ],
                        'items' => [
                            [
                                'id' => 'id1',
                                'name' => 'A fäncy Name'.PHP_EOL,
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
                                        'templated' => false,
                                        'rels' => [],
                                        'attributes' => [
                                            'method' => 'GET',
                                        ],
                                    ],
                                    'update' => [
                                        'href' => 'http://test.com/items/id1',
                                        'templated' => false,
                                        'rels' => [],
                                        'attributes' => [
                                            'method' => 'PUT',
                                        ],
                                    ],
                                    'delete' => [
                                        'href' => 'http://test.com/items/id1',
                                        'templated' => false,
                                        'rels' => [],
                                        'attributes' => [
                                            'method' => 'DELETE',
                                        ],
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
                                        'templated' => false,
                                        'rels' => [],
                                        'attributes' => [
                                            'method' => 'GET',
                                        ],
                                    ],
                                    'update' => [
                                        'href' => 'http://test.com/items/id2',
                                        'templated' => false,
                                        'rels' => [],
                                        'attributes' => [
                                            'method' => 'PUT',
                                        ],
                                    ],
                                    'delete' => [
                                        'href' => 'http://test.com/items/id2',
                                        'templated' => false,
                                        'rels' => [],
                                        'attributes' => [
                                            'method' => 'DELETE',
                                        ],
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
                                        'templated' => false,
                                        'rels' => [],
                                        'attributes' => [
                                            'method' => 'GET',
                                        ],
                                    ],
                                    'update' => [
                                        'href' => 'http://test.com/items/id3',
                                        'templated' => false,
                                        'rels' => [],
                                        'attributes' => [
                                            'method' => 'PUT',
                                        ],
                                    ],
                                    'delete' => [
                                        'href' => 'http://test.com/items/id3',
                                        'templated' => false,
                                        'rels' => [],
                                        'attributes' => [
                                            'method' => 'DELETE',
                                        ],
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
                ],
            ],
        ];
    }
}
