<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization\Transformer;

use Chubbyphp\Serialization\Transformer\YamlTransformer;

/**
 * @covers \Chubbyphp\Serialization\Transformer\YamlTransformer
 */
class YamlTransformerTest extends AbstractTransformerTest
{
    /**
     * @dataProvider dataProvider
     *
     * @param array $data
     */
    public function testFormat(array $data)
    {
        $yamlTransformer = new YamlTransformer();

        $yaml = $yamlTransformer->transform($data);

        $expectedYaml = <<<EOD
search:
    page: 1
    perPage: 10
    search: null
    sort: name
    order: asc
    _embedded:
        mainItem:
            item:
                id: id1
                name: 'A fancy Name'
                treeValues:
                    1:
                        2: 3
                progress: 76.8
                active: true
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
                item:
                    id: id1
                    name: 'A fancy Name'
                    treeValues:
                        1:
                            2: 3
                    progress: 76.8
                    active: true
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
                item:
                    id: id2
                    name: 'B fancy Name'
                    treeValues:
                        1:
                            2: 3
                            3: 4
                    progress: 24.7
                    active: true
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
                item:
                    id: id3
                    name: 'C fancy Name'
                    treeValues:
                        1:
                            2: 3
                            3: 4
                            6: 7
                    progress: !!float 100
                    active: false
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
        search:
            href: 'http://test.com/items/?page=1&perPage=10&sort=name&order=asc'
            method: GET
        create:
            href: 'http://test.com/items/'
            method: POST
EOD;

        self::assertEquals($expectedYaml, $yaml);
    }
}
