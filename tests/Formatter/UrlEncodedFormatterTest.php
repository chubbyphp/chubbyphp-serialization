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
     * @param array $data
     */
    public function testFormat(array $data)
    {
        $urlEncodedFormatter = new UrlEncodedFormatter();
        $urlEncoded = $urlEncodedFormatter->format($data);

        $expectedUrlEncoded = 'name=name1&_embedded%5BembeddedModel%5D%5Bname%5D=embedded1&_embedded%5BembeddedModels' .
            '%5D%5B0%5D%5Bname%5D=embedded2&_embedded%5BembeddedModels%5D%5B1%5D%5Bname%5D=embedded3&_embedded' .
            '%5BembeddedModels%5D%5B2%5D%5Bname%5D=embedded4&_links%5Bname%3Aread%5D%5Bhref%5D=http%3A%2F%2Ftest.com' .
            '%2Fmodels%2Fid1&_links%5Bname%3Aread%5D%5Bmethod%5D=GET&_links%5Bname%3Aupdate%5D%5Bhref%5D=http%3A%2F%2F' .
            'test.com%2Fmodels%2Fid1&_links%5Bname%3Aupdate%5D%5Bmethod%5D=PUT&_links%5Bname%3Adelete%5D%5Bhref%5D=' .
            'http%3A%2F%2Ftest.com%2Fmodels%2Fid1&_links%5Bname%3Adelete%5D%5Bmethod%5D=DELETE';

        self::assertEquals($expectedUrlEncoded, $urlEncoded);
    }
}
