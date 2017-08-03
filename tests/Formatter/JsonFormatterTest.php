<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization\Formatter;

use Chubbyphp\Serialization\Formatter\JsonFormatter;

/**
 * @covers \Chubbyphp\Serialization\Formatter\JsonFormatter
 */
class JsonFormatterTest extends AbstractFormatterTest
{
    /**
     * @dataProvider dataProvider
     * @param array $data
     */
    public function testFormat(array $data)
    {
        $jsonFormatter = new JsonFormatter(JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);

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
            "href": "http://test.com/models/id1",
            "method": "GET"
        },
        "name:update": {
            "href": "http://test.com/models/id1",
            "method": "PUT"
        },
        "name:delete": {
            "href": "http://test.com/models/id1",
            "method": "DELETE"
        }
    }
}
EOD;
        self::assertEquals($expectedJson, $json);
    }
}
