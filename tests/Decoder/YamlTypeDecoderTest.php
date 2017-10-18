<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization\Encoder;

use Chubbyphp\Serialization\Encoder\YamlTypeEncoder;
use Chubbyphp\Serialization\SerializerRuntimeException;

/**
 * @covers \Chubbyphp\Serialization\Encoder\YamlTypeEncoder
 */
class YamlTypeEncoderTest extends AbstractTypeEncoderTest
{
    public function testGetContentType()
    {
        $encoder = new YamlTypeEncoder();

        self::assertSame('application/x-yaml', $encoder->getContentType());
    }

    /**
     * @dataProvider getExpectedData
     *
     * @param array $expectedData
     */
    public function testDecode(array $expectedData)
    {
        $yaml = <<<EOD
page: 1
perPage: 10
search: null
sort: name
order: asc
_embedded:
    mainItem:
        id: id1
        name: 'A fancy Name'
        treeValues:
            1:
                2: 3
        progress: 76.8
        active: true
        _type: item
        _links:
            read:
                href: 'http://test.com/items/id1'
                method: GET
            update:
                href: 'http://test.com/items/id1'
                method: PUT
            delete:
                href: 'http://test.com/items/id1'
                method: DELETE
    items:
        -
            id: id1
            name: 'A fancy Name'
            treeValues:
                1:
                    2: 3
            progress: 76.8
            active: true
            _type: item
            _links:
                read:
                    href: 'http://test.com/items/id1'
                    method: GET
                update:
                    href: 'http://test.com/items/id1'
                    method: PUT
                delete:
                    href: 'http://test.com/items/id1'
                    method: DELETE
        -
            id: id2
            name: 'B fancy Name'
            treeValues:
                1:
                    2: 3
                    3: 4
            progress: 24.7
            active: true
            _type: item
            _links:
                read:
                    href: 'http://test.com/items/id2'
                    method: GET
                update:
                    href: 'http://test.com/items/id2'
                    method: PUT
                delete:
                    href: 'http://test.com/items/id2'
                    method: DELETE
        -
            id: id3
            name: 'C fancy Name'
            treeValues:
                1:
                    2: 3
                    3: 4
                    6: 7
            progress: !!float 100
            active: false
            _type: item
            _links:
                read:
                    href: 'http://test.com/items/id3'
                    method: GET
                update:
                    href: 'http://test.com/items/id3'
                    method: PUT
                delete:
                    href: 'http://test.com/items/id3'
                    method: DELETE
_links:
    self:
        href: 'http://test.com/items/?page=1&perPage=10&sort=name&order=asc'
        method: GET
    create:
        href: 'http://test.com/items/'
        method: POST
_type: search
EOD;

        $encoder = new YamlTypeEncoder();

        self::assertEquals($expectedData, $encoder->encode($yaml));
    }

    public function testTypes()
    {
        $yaml = <<<EOD
id: id1
name: 'A fancy Name'
treeValues:
    1:
        2: 3
progress: 76.8
active: true
EOD;

        $encoder = new YamlTypeEncoder();

        $data = $encoder->encode($yaml);

        self::assertSame('id1', $data['id']);
        self::assertSame('A fancy Name', $data['name']);
        self::assertSame([1 => [2 => 3]], $data['treeValues']);
        self::assertSame(76.8, $data['progress']);
        self::assertSame(true, $data['active']);
    }

    public function testInvalidDecode()
    {
        self::expectException(SerializerRuntimeException::class);
        self::expectExceptionMessage('Data is not parsable with content-type: "application/x-yaml"');
        $encoderType = new YamlTypeEncoder();
        $encoderType->encode('====');
    }
}
