<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization\Unit\Encoder;

use Chubbyphp\Serialization\Encoder\JsonTypeEncoder;

/**
 * @covers \Chubbyphp\Serialization\Encoder\JsonTypeEncoder
 *
 * @internal
 */
final class JsonTypeEncoderTest extends AbstractTypeEncoderTest
{
    public function testContentType(): void
    {
        $encoder = new JsonTypeEncoder();

        self::assertSame('application/json', $encoder->getContentType());
    }

    /**
     * @dataProvider dataProvider
     */
    public function testFormat(array $data): void
    {
        $jsonencoder = new JsonTypeEncoder(true);

        $json = $jsonencoder->encode($data);

        $expectedJson = <<<'EOT'
{
    "page": 1,
    "perPage": 10,
    "search": null,
    "sort": "name",
    "order": "asc",
    "_embedded": {
        "mainItem": {
            "id": "id1",
            "name": "A fäncy Name\n",
            "treeValues": {
                "1": {
                    "2": 3
                }
            },
            "progress": 76.8,
            "active": true,
            "_type": "item",
            "_links": {
                "read": {
                    "href": "http://test.com/items/id1",
                    "templated": false,
                    "rels": [],
                    "attributes": {
                        "method": "GET"
                    }
                },
                "update": {
                    "href": "http://test.com/items/id1",
                    "templated": false,
                    "rels": [],
                    "attributes": {
                        "method": "PUT"
                    }
                },
                "delete": {
                    "href": "http://test.com/items/id1",
                    "templated": false,
                    "rels": [],
                    "attributes": {
                        "method": "DELETE"
                    }
                }
            }
        },
        "items": [
            {
                "id": "id1",
                "name": "A fäncy Name\n",
                "treeValues": {
                    "1": {
                        "2": 3
                    }
                },
                "progress": 76.8,
                "active": true,
                "_type": "item",
                "_links": {
                    "read": {
                        "href": "http://test.com/items/id1",
                        "templated": false,
                        "rels": [],
                        "attributes": {
                            "method": "GET"
                        }
                    },
                    "update": {
                        "href": "http://test.com/items/id1",
                        "templated": false,
                        "rels": [],
                        "attributes": {
                            "method": "PUT"
                        }
                    },
                    "delete": {
                        "href": "http://test.com/items/id1",
                        "templated": false,
                        "rels": [],
                        "attributes": {
                            "method": "DELETE"
                        }
                    }
                }
            },
            {
                "id": "id2",
                "name": "B fancy Name",
                "treeValues": {
                    "1": {
                        "2": 3,
                        "3": 4
                    }
                },
                "progress": 24.7,
                "active": true,
                "_type": "item",
                "_links": {
                    "read": {
                        "href": "http://test.com/items/id2",
                        "templated": false,
                        "rels": [],
                        "attributes": {
                            "method": "GET"
                        }
                    },
                    "update": {
                        "href": "http://test.com/items/id2",
                        "templated": false,
                        "rels": [],
                        "attributes": {
                            "method": "PUT"
                        }
                    },
                    "delete": {
                        "href": "http://test.com/items/id2",
                        "templated": false,
                        "rels": [],
                        "attributes": {
                            "method": "DELETE"
                        }
                    }
                }
            },
            {
                "id": "id3",
                "name": "C fancy Name",
                "treeValues": {
                    "1": {
                        "2": 3,
                        "3": 4,
                        "6": 7
                    }
                },
                "progress": 100.0,
                "active": false,
                "_type": "item",
                "_links": {
                    "read": {
                        "href": "http://test.com/items/id3",
                        "templated": false,
                        "rels": [],
                        "attributes": {
                            "method": "GET"
                        }
                    },
                    "update": {
                        "href": "http://test.com/items/id3",
                        "templated": false,
                        "rels": [],
                        "attributes": {
                            "method": "PUT"
                        }
                    },
                    "delete": {
                        "href": "http://test.com/items/id3",
                        "templated": false,
                        "rels": [],
                        "attributes": {
                            "method": "DELETE"
                        }
                    }
                }
            }
        ]
    },
    "_links": {
        "self": {
            "href": "http://test.com/items/?page=1&perPage=10&sort=name&order=asc",
            "method": "GET"
        },
        "create": {
            "href": "http://test.com/items/",
            "method": "POST"
        }
    },
    "_type": "search"
}
EOT;
        self::assertEquals($expectedJson, $json);
    }

    public function testFormatJsonIgnoreInvalidUtf8WithPrettyPrint(): void
    {
        $data = [
            'page' => 1,
            'perPage' => 10,
            'search' => null,
            'sort' => 'name',
            'order' => 'asc',
            'filter' => utf8_decode('fieldÁ==value'),
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
        ];

        $jsonencoder = new JsonTypeEncoder(true);

        $json = $jsonencoder->encode($data);

        $expectedJson = <<<'EOT'
{
    "page": 1,
    "perPage": 10,
    "search": null,
    "sort": "name",
    "order": "asc",
    "filter": "field==value",
    "_links": {
        "self": {
            "href": "http://test.com/items/?page=1&perPage=10&sort=name&order=asc",
            "method": "GET"
        },
        "create": {
            "href": "http://test.com/items/",
            "method": "POST"
        }
    },
    "_type": "search"
}
EOT;
        self::assertEquals($expectedJson, $json);
    }
}
