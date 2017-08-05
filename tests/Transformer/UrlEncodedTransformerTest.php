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

        $expectedUrlEncoded = 'search%5Bpage%5D=1&search%5BperPage%5D=10&search%5Bsort%5D=name&search%5Border%5D=asc&'
            . 'search%5B_embedded%5D%5Bitems%5D%5B0%5D%5Bid%5D=id1&search%5B_embedded%5D%5Bitems%5D%5B0%5D%5Bname%5D='
            . 'A+fancy+Name&search%5B_embedded%5D%5Bitems%5D%5B0%5D%5Bprogress%5D=76.8&search%5B_embedded%5D%5Bitems%5D'
            . '%5B0%5D%5Bactive%5D=1&search%5B_embedded%5D%5Bitems%5D%5B0%5D%5B_links%5D%5Bread%5D%5Bhref%5D=http%3A%2F'
            . '%2Ftest.com%2Fitems%2Fid1&search%5B_embedded%5D%5Bitems%5D%5B0%5D%5B_links%5D%5Bread%5D%5Bmethod%5D=GET&'
            . 'search%5B_embedded%5D%5Bitems%5D%5B0%5D%5B_links%5D%5Bupdate%5D%5Bhref%5D=http%3A%2F%2Ftest.com%2Fitems'
            . '%2Fid1&search%5B_embedded%5D%5Bitems%5D%5B0%5D%5B_links%5D%5Bupdate%5D%5Bmethod%5D=PUT&search%5B'
            . '_embedded%5D%5Bitems%5D%5B0%5D%5B_links%5D%5Bdelete%5D%5Bhref%5D=http%3A%2F%2Ftest.com%2Fitems%2Fid1&'
            . 'search%5B_embedded%5D%5Bitems%5D%5B0%5D%5B_links%5D%5Bdelete%5D%5Bmethod%5D=DELETE&search%5B_embedded%5D'
            . '%5Bitems%5D%5B1%5D%5Bid%5D=id2&search%5B_embedded%5D%5Bitems%5D%5B1%5D%5Bname%5D=B+fancy+Name&search%5B'
            . '_embedded%5D%5Bitems%5D%5B1%5D%5Bprogress%5D=24.7&search%5B_embedded%5D%5Bitems%5D%5B1%5D%5Bactive%5D=1&'
            . 'search%5B_embedded%5D%5Bitems%5D%5B1%5D%5B_links%5D%5Bread%5D%5Bhref%5D=http%3A%2F%2Ftest.com%2Fitems%2F'
            . 'id2&search%5B_embedded%5D%5Bitems%5D%5B1%5D%5B_links%5D%5Bread%5D%5Bmethod%5D=GET&search%5B_embedded%5D'
            . '%5Bitems%5D%5B1%5D%5B_links%5D%5Bupdate%5D%5Bhref%5D=http%3A%2F%2Ftest.com%2Fitems%2Fid2&search%5B'
            . '_embedded%5D%5Bitems%5D%5B1%5D%5B_links%5D%5Bupdate%5D%5Bmethod%5D=PUT&search%5B_embedded%5D%5Bitems%5D'
            . '%5B1%5D%5B_links%5D%5Bdelete%5D%5Bhref%5D=http%3A%2F%2Ftest.com%2Fitems%2Fid2&search%5B_embedded%5D%5B'
            . 'items%5D%5B1%5D%5B_links%5D%5Bdelete%5D%5Bmethod%5D=DELETE&search%5B_embedded%5D%5Bitems%5D%5B2%5D%5Bid'
            . '%5D=id3&search%5B_embedded%5D%5Bitems%5D%5B2%5D%5Bname%5D=C+fancy+Name&search%5B_embedded%5D%5Bitems%5D'
            . '%5B2%5D%5Bprogress%5D=100&search%5B_embedded%5D%5Bitems%5D%5B2%5D%5Bactive%5D=0&search%5B_embedded%5D%5B'
            . 'items%5D%5B2%5D%5B_links%5D%5Bread%5D%5Bhref%5D=http%3A%2F%2Ftest.com%2Fitems%2Fid3&search%5B_embedded'
            . '%5D%5Bitems%5D%5B2%5D%5B_links%5D%5Bread%5D%5Bmethod%5D=GET&search%5B_embedded%5D%5Bitems%5D%5B2%5D%5B'
            . '_links%5D%5Bupdate%5D%5Bhref%5D=http%3A%2F%2Ftest.com%2Fitems%2Fid3&search%5B_embedded%5D%5Bitems%5D%5B2'
            . '%5D%5B_links%5D%5Bupdate%5D%5Bmethod%5D=PUT&search%5B_embedded%5D%5Bitems%5D%5B2%5D%5B_links%5D%5Bdelete'
            . '%5D%5Bhref%5D=http%3A%2F%2Ftest.com%2Fitems%2Fid3&search%5B_embedded%5D%5Bitems%5D%5B2%5D%5B_links%5D%5B'
            . 'delete%5D%5Bmethod%5D=DELETE&search%5B_links%5D%5Bself%5D%5Bhref%5D=http%3A%2F%2Ftest.com%2Fitems%2F%3F'
            . 'page%3D1%26perPage%3D10%26sort%3Dname%26order%3Dasc&search%5B_links%5D%5Bself%5D%5Bmethod%5D=GET&search'
            . '%5B_links%5D%5Bcreate%5D%5Bhref%5D=http%3A%2F%2Ftest.com%2Fitems%2F&search%5B_links%5D%5Bcreate%5D%5B'
            . 'method%5D=POST';

        self::assertEquals($expectedUrlEncoded, $urlEncoded);
    }
}
