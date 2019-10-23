<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization\Unit\Encoder;

use Chubbyphp\Serialization\Encoder\UrlEncodedTypeEncoder;

/**
 * @covers \Chubbyphp\Serialization\Encoder\UrlEncodedTypeEncoder
 *
 * @internal
 */
final class UrlEncodedTypeEncoderTest extends AbstractTypeEncoderTest
{
    public function testContentType(): void
    {
        $encoder = new UrlEncodedTypeEncoder();

        self::assertSame('application/x-www-form-urlencoded', $encoder->getContentType());
    }

    /**
     * @dataProvider dataProvider
     *
     * @param array $data
     */
    public function testFormat(array $data): void
    {
        $urlEncodedencoder = new UrlEncodedTypeEncoder();
        $urlEncoded = $urlEncodedencoder->encode($data);

        $expectedUrlEncoded = 'page=1&perPage=10&sort=name&order=asc&_embedded[mainItem][id]=id1&_embedded[mainItem][name]=A+f%C3%A4ncy+Name%0A&_embedded[mainItem][treeValues][1][2]=3&_embedded[mainItem][progress]=76.8&_embedded[mainItem][active]=true&_embedded[mainItem][_type]=item&_embedded[mainItem][_links][read][href]=http%3A%2F%2Ftest.com%2Fitems%2Fid1&_embedded[mainItem][_links][read][templated]=false&&_embedded[mainItem][_links][read][attributes][method]=GET&_embedded[mainItem][_links][update][href]=http%3A%2F%2Ftest.com%2Fitems%2Fid1&_embedded[mainItem][_links][update][templated]=false&&_embedded[mainItem][_links][update][attributes][method]=PUT&_embedded[mainItem][_links][delete][href]=http%3A%2F%2Ftest.com%2Fitems%2Fid1&_embedded[mainItem][_links][delete][templated]=false&&_embedded[mainItem][_links][delete][attributes][method]=DELETE&_embedded[items][0][id]=id1&_embedded[items][0][name]=A+f%C3%A4ncy+Name%0A&_embedded[items][0][treeValues][1][2]=3&_embedded[items][0][progress]=76.8&_embedded[items][0][active]=true&_embedded[items][0][_type]=item&_embedded[items][0][_links][read][href]=http%3A%2F%2Ftest.com%2Fitems%2Fid1&_embedded[items][0][_links][read][templated]=false&&_embedded[items][0][_links][read][attributes][method]=GET&_embedded[items][0][_links][update][href]=http%3A%2F%2Ftest.com%2Fitems%2Fid1&_embedded[items][0][_links][update][templated]=false&&_embedded[items][0][_links][update][attributes][method]=PUT&_embedded[items][0][_links][delete][href]=http%3A%2F%2Ftest.com%2Fitems%2Fid1&_embedded[items][0][_links][delete][templated]=false&&_embedded[items][0][_links][delete][attributes][method]=DELETE&_embedded[items][1][id]=id2&_embedded[items][1][name]=B+fancy+Name&_embedded[items][1][treeValues][1][2]=3&_embedded[items][1][treeValues][1][3]=4&_embedded[items][1][progress]=24.7&_embedded[items][1][active]=true&_embedded[items][1][_type]=item&_embedded[items][1][_links][read][href]=http%3A%2F%2Ftest.com%2Fitems%2Fid2&_embedded[items][1][_links][read][templated]=false&&_embedded[items][1][_links][read][attributes][method]=GET&_embedded[items][1][_links][update][href]=http%3A%2F%2Ftest.com%2Fitems%2Fid2&_embedded[items][1][_links][update][templated]=false&&_embedded[items][1][_links][update][attributes][method]=PUT&_embedded[items][1][_links][delete][href]=http%3A%2F%2Ftest.com%2Fitems%2Fid2&_embedded[items][1][_links][delete][templated]=false&&_embedded[items][1][_links][delete][attributes][method]=DELETE&_embedded[items][2][id]=id3&_embedded[items][2][name]=C+fancy+Name&_embedded[items][2][treeValues][1][2]=3&_embedded[items][2][treeValues][1][3]=4&_embedded[items][2][treeValues][1][6]=7&_embedded[items][2][progress]=100.0&_embedded[items][2][active]=false&_embedded[items][2][_type]=item&_embedded[items][2][_links][read][href]=http%3A%2F%2Ftest.com%2Fitems%2Fid3&_embedded[items][2][_links][read][templated]=false&&_embedded[items][2][_links][read][attributes][method]=GET&_embedded[items][2][_links][update][href]=http%3A%2F%2Ftest.com%2Fitems%2Fid3&_embedded[items][2][_links][update][templated]=false&&_embedded[items][2][_links][update][attributes][method]=PUT&_embedded[items][2][_links][delete][href]=http%3A%2F%2Ftest.com%2Fitems%2Fid3&_embedded[items][2][_links][delete][templated]=false&&_embedded[items][2][_links][delete][attributes][method]=DELETE&_links[self][href]=http%3A%2F%2Ftest.com%2Fitems%2F%3Fpage%3D1%26perPage%3D10%26sort%3Dname%26order%3Dasc&_links[self][method]=GET&_links[create][href]=http%3A%2F%2Ftest.com%2Fitems%2F&_links[create][method]=POST&_type=search';

        self::assertEquals($expectedUrlEncoded, $urlEncoded);
    }

    public function testInvalidValueAsString(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Unsupported data type: object');

        $urlEncodedencoder = new UrlEncodedTypeEncoder();
        $urlEncodedencoder->encode(['key' => new \stdClass()]);
    }
}
