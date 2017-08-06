<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization\Transformer;

use Chubbyphp\Serialization\Transformer\UrlEncodedTransformer;

/**
 * @covers \Chubbyphp\Serialization\Transformer\UrlEncodedTransformer
 */
class UrlEncodedTransformerTest extends AbstractTransformerTest
{
    /**
     * @dataProvider dataProvider
     *
     * @param array $data
     */
    public function testFormat(array $data)
    {
        $urlEncodedTransformer = new UrlEncodedTransformer();
        $urlEncoded = $urlEncodedTransformer->transform($data);

        $expectedUrlEncoded = 'search[page]=1&search[perPage]=10&search[search]=&search[sort]=name&search[order]=asc&search[_embedded][mainItem][item][id]=id1&search[_embedded][mainItem][item][name]=A+fancy+Name&search[_embedded][mainItem][item][treeValues][1][2]=3&search[_embedded][mainItem][item][progress]=76.8&search[_embedded][mainItem][item][active]=1&search[_embedded][mainItem][item][_links][read][href]=http%3A%2F%2Ftest.com%2Fitems%2Fid1&search[_embedded][mainItem][item][_links][read][method]=GET&search[_embedded][mainItem][item][_links][update][href]=http%3A%2F%2Ftest.com%2Fitems%2Fid1&search[_embedded][mainItem][item][_links][update][method]=PUT&search[_embedded][mainItem][item][_links][delete][href]=http%3A%2F%2Ftest.com%2Fitems%2Fid1&search[_embedded][mainItem][item][_links][delete][method]=DELETE&search[_embedded][items][0][item][id]=id1&search[_embedded][items][0][item][name]=A+fancy+Name&search[_embedded][items][0][item][treeValues][1][2]=3&search[_embedded][items][0][item][progress]=76.8&search[_embedded][items][0][item][active]=1&search[_embedded][items][0][item][_links][read][href]=http%3A%2F%2Ftest.com%2Fitems%2Fid1&search[_embedded][items][0][item][_links][read][method]=GET&search[_embedded][items][0][item][_links][update][href]=http%3A%2F%2Ftest.com%2Fitems%2Fid1&search[_embedded][items][0][item][_links][update][method]=PUT&search[_embedded][items][0][item][_links][delete][href]=http%3A%2F%2Ftest.com%2Fitems%2Fid1&search[_embedded][items][0][item][_links][delete][method]=DELETE&search[_embedded][items][1][item][id]=id2&search[_embedded][items][1][item][name]=B+fancy+Name&search[_embedded][items][1][item][treeValues][1][2]=3&search[_embedded][items][1][item][treeValues][1][3]=4&search[_embedded][items][1][item][progress]=24.7&search[_embedded][items][1][item][active]=1&search[_embedded][items][1][item][_links][read][href]=http%3A%2F%2Ftest.com%2Fitems%2Fid2&search[_embedded][items][1][item][_links][read][method]=GET&search[_embedded][items][1][item][_links][update][href]=http%3A%2F%2Ftest.com%2Fitems%2Fid2&search[_embedded][items][1][item][_links][update][method]=PUT&search[_embedded][items][1][item][_links][delete][href]=http%3A%2F%2Ftest.com%2Fitems%2Fid2&search[_embedded][items][1][item][_links][delete][method]=DELETE&search[_embedded][items][2][item][id]=id3&search[_embedded][items][2][item][name]=C+fancy+Name&search[_embedded][items][2][item][treeValues][1][2]=3&search[_embedded][items][2][item][treeValues][1][3]=4&search[_embedded][items][2][item][treeValues][1][6]=7&search[_embedded][items][2][item][progress]=100&search[_embedded][items][2][item][active]=&search[_embedded][items][2][item][_links][read][href]=http%3A%2F%2Ftest.com%2Fitems%2Fid3&search[_embedded][items][2][item][_links][read][method]=GET&search[_embedded][items][2][item][_links][update][href]=http%3A%2F%2Ftest.com%2Fitems%2Fid3&search[_embedded][items][2][item][_links][update][method]=PUT&search[_embedded][items][2][item][_links][delete][href]=http%3A%2F%2Ftest.com%2Fitems%2Fid3&search[_embedded][items][2][item][_links][delete][method]=DELETE&search[_links][search][href]=http%3A%2F%2Ftest.com%2Fitems%2F%3Fpage%3D1%26perPage%3D10%26sort%3Dname%26order%3Dasc&search[_links][search][method]=GET&search[_links][create][href]=http%3A%2F%2Ftest.com%2Fitems%2F&search[_links][create][method]=POST';

        self::assertEquals($expectedUrlEncoded, $urlEncoded);
    }
}
