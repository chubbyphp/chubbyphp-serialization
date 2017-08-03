<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization\Formatter;

use Chubbyphp\Serialization\Formatter\XmlFormatter;

class XmlFormatterTest extends AbstractFormatterTest
{
    /**
     * @dataProvider dataProvider
     * @param array $data
     */
    public function testFormat(array $data)
    {
        $xmlFormatter = new XmlFormatter(true);
        $xml = $xmlFormatter->format($data);

        $expectedXml = <<<EOD
<?xml version="1.0" encoding="UTF-8"?>
<name>name1</name>
<_embedded>
  <embeddedModel>
    <name>embedded1</name>
  </embeddedModel>
  <embeddedModels>
    <embeddedModel>
      <name>embedded2</name>
    </embeddedModel>
    <embeddedModel>
      <name>embedded3</name>
    </embeddedModel>
    <embeddedModel>
      <name>embedded4</name>
    </embeddedModel>
  </embeddedModels>
</_embedded>
<_links>
  <name:read>
    <href>http://test.com/models/id1</href>
    <method>GET</method>
  </name:read>
  <name:update>
    <href>http://test.com/models/id1</href>
    <method>PUT</method>
  </name:update>
  <name:delete>
    <href>http://test.com/models/id1</href>
    <method>DELETE</method>
  </name:delete>
</_links>

EOD;

        self::assertEquals($expectedXml, $xml);
    }
}
