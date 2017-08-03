<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization\Formatter;

abstract class AbstractFormatterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider dataProvider
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
                    'name' => 'name1',
                    'active' => false,
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
                ]
            ]
        ];
    }
}
