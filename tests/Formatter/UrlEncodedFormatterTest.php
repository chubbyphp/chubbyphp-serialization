<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization\Formatter;

use Chubbyphp\Serialization\Formatter\UrlEncodedFormatter;

/**
 * @covers \Chubbyphp\Serialization\Formatter\UrlEncodedFormatter
 */
class UrlEncodedFormatterTest extends AbstractFormatterTest
{
    /**
     * @dataProvider dataProvider
     *
     * @param array $data
     */
    public function testFormat(array $data)
    {
        $urlEncodedFormatter = new UrlEncodedFormatter();
        $urlEncoded = $urlEncodedFormatter->format($data);

        $expectedUrlEncoded = 'page=1&perPage=10&sort=name&order=asc&_embedded%5Bitems%5D%5B0%5D%5Bid%5D=id1&_embedded'
            .'%5Bitems%5D%5B0%5D%5Bname%5D=A+fancy+Name&_embedded%5Bitems%5D%5B0%5D%5Bprogress%5D=76.8&_embedded%5B'
            .'items%5D%5B0%5D%5Bactive%5D=1&_embedded%5Bitems%5D%5B0%5D%5B_links%5D%5Bitem%3Aread%5D%5Bhref%5D='
            .'http%3A%2F%2Ftest.com%2Fitems%2Fid1&_embedded%5Bitems%5D%5B0%5D%5B_links%5D%5Bitem%3Aread%5D%5Bmethod%5D'
            .'=GET&_embedded%5Bitems%5D%5B0%5D%5B_links%5D%5Bitem%3Aupdate%5D%5Bhref%5D=http%3A%2F%2Ftest.com%2Fitems'
            .'%2Fid1&_embedded%5Bitems%5D%5B0%5D%5B_links%5D%5Bitem%3Aupdate%5D%5Bmethod%5D=PUT&_embedded%5Bitems%5D'
            .'%5B0%5D%5B_links%5D%5Bitem%3Adelete%5D%5Bhref%5D=http%3A%2F%2Ftest.com%2Fitems%2Fid1&_embedded%5Bitems'
            .'%5D%5B0%5D%5B_links%5D%5Bitem%3Adelete%5D%5Bmethod%5D=DELETE&_embedded%5Bitems%5D%5B1%5D%5Bid%5D=id2&'
            .'_embedded%5Bitems%5D%5B1%5D%5Bname%5D=B+fancy+Name&_embedded%5Bitems%5D%5B1%5D%5Bprogress%5D=24&'
            .'_embedded%5Bitems%5D%5B1%5D%5Bactive%5D=1&_embedded%5Bitems%5D%5B1%5D%5B_links%5D%5Bitem%3Aread%5D%5B'
            .'href%5D=http%3A%2F%2Ftest.com%2Fitems%2Fid2&_embedded%5Bitems%5D%5B1%5D%5B_links%5D%5Bitem%3Aread%5D'
            .'%5Bmethod%5D=GET&_embedded%5Bitems%5D%5B1%5D%5B_links%5D%5Bitem%3Aupdate%5D%5Bhref%5D=http%3A%2F%2Ftest'
            .'.com%2Fitems%2Fid2&_embedded%5Bitems%5D%5B1%5D%5B_links%5D%5Bitem%3Aupdate%5D%5Bmethod%5D=PUT&_embedded'
            .'%5Bitems%5D%5B1%5D%5B_links%5D%5Bitem%3Adelete%5D%5Bhref%5D=http%3A%2F%2Ftest.com%2Fitems%2Fid2&'
            .'_embedded%5Bitems%5D%5B1%5D%5B_links%5D%5Bitem%3Adelete%5D%5Bmethod%5D=DELETE&_embedded%5Bitems%5D%5B2'
            .'%5D%5Bid%5D=id3&_embedded%5Bitems%5D%5B2%5D%5Bname%5D=C+fancy+Name&_embedded%5Bitems%5D%5B2%5D%5B'
            .'progress%5D=100&_embedded%5Bitems%5D%5B2%5D%5Bactive%5D=0&_embedded%5Bitems%5D%5B2%5D%5B_links%5D%5B'
            .'item%3Aread%5D%5Bhref%5D=http%3A%2F%2Ftest.com%2Fitems%2Fid3&_embedded%5Bitems%5D%5B2%5D%5B_links%5D%5B'
            .'item%3Aread%5D%5Bmethod%5D=GET&_embedded%5Bitems%5D%5B2%5D%5B_links%5D%5Bitem%3Aupdate%5D%5Bhref%5D=http'
            .'%3A%2F%2Ftest.com%2Fitems%2Fid3&_embedded%5Bitems%5D%5B2%5D%5B_links%5D%5Bitem%3Aupdate%5D%5Bmethod%5D='
            .'PUT&_embedded%5Bitems%5D%5B2%5D%5B_links%5D%5Bitem%3Adelete%5D%5Bhref%5D=http%3A%2F%2Ftest.com%2Fitems'
            .'%2Fid3&_embedded%5Bitems%5D%5B2%5D%5B_links%5D%5Bitem%3Adelete%5D%5Bmethod%5D=DELETE&_links%5Bself%5D'
            .'%5Bhref%5D=http%3A%2F%2Ftest.com%2Fitems%2F%3Fpage%3D1%26perPage%3D10%26sort%3Dname%26order%3Dasc&_links'
            .'%5Bself%5D%5Bmethod%5D=GET&_links%5Bitem%3Acreate%5D%5Bhref%5D=http%3A%2F%2Ftest.com%2Fitems%2F&_links'
            .'%5Bitem%3Acreate%5D%5Bmethod%5D=POST';

        self::assertEquals($expectedUrlEncoded, $urlEncoded);
    }
}
