<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization\Formatter;

use Chubbyphp\Serialization\Formatter\XmlFormatter;

/**
 * @covers \Chubbyphp\Serialization\Formatter\XmlFormatter
 */
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
<name type="string">name1</name>
<active type="boolean">false</active>
<_embedded>
  <embeddedModel>
    <name type="string">embedded1</name>
  </embeddedModel>
  <embeddedModels>
    <embeddedModel>
      <name type="string">embedded2</name>
    </embeddedModel>
    <embeddedModel>
      <name type="string">embedded3</name>
    </embeddedModel>
    <embeddedModel>
      <name type="string">embedded4</name>
    </embeddedModel>
  </embeddedModels>
</_embedded>
<_links>
  <name:read>
    <href type="string">http://test.com/models/id1</href>
    <method type="string">GET</method>
  </name:read>
  <name:update>
    <href type="string">http://test.com/models/id1</href>
    <method type="string">PUT</method>
  </name:update>
  <name:delete>
    <href type="string">http://test.com/models/id1</href>
    <method type="string">DELETE</method>
  </name:delete>
</_links>

EOD;

        self::assertEquals($expectedXml, $xml);
    }
}
