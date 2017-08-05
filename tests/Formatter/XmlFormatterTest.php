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
<page type="integer">1</page>
<perPage type="integer">10</perPage>
<search/>
<sort type="string"><![CDATA[name]]></sort>
<order type="string"><![CDATA[asc]]></order>
<_embedded>
  <items>
    <item>
      <id type="string"><![CDATA[id1]]></id>
      <name type="string"><![CDATA[A fancy Name]]></name>
      <progress type="float">76.8</progress>
      <active type="boolean">true</active>
      <_links>
        <item:read>
          <href type="string"><![CDATA[http://test.com/items/id1]]></href>
          <method type="string"><![CDATA[GET]]></method>
        </item:read>
        <item:update>
          <href type="string"><![CDATA[http://test.com/items/id1]]></href>
          <method type="string"><![CDATA[PUT]]></method>
        </item:update>
        <item:delete>
          <href type="string"><![CDATA[http://test.com/items/id1]]></href>
          <method type="string"><![CDATA[DELETE]]></method>
        </item:delete>
      </_links>
    </item>
    <item>
      <id type="string"><![CDATA[id2]]></id>
      <name type="string"><![CDATA[B fancy Name]]></name>
      <progress type="float">24</progress>
      <active type="boolean">true</active>
      <_links>
        <item:read>
          <href type="string"><![CDATA[http://test.com/items/id2]]></href>
          <method type="string"><![CDATA[GET]]></method>
        </item:read>
        <item:update>
          <href type="string"><![CDATA[http://test.com/items/id2]]></href>
          <method type="string"><![CDATA[PUT]]></method>
        </item:update>
        <item:delete>
          <href type="string"><![CDATA[http://test.com/items/id2]]></href>
          <method type="string"><![CDATA[DELETE]]></method>
        </item:delete>
      </_links>
    </item>
    <item>
      <id type="string"><![CDATA[id3]]></id>
      <name type="string"><![CDATA[C fancy Name]]></name>
      <progress type="float">100</progress>
      <active type="boolean">false</active>
      <_links>
        <item:read>
          <href type="string"><![CDATA[http://test.com/items/id3]]></href>
          <method type="string"><![CDATA[GET]]></method>
        </item:read>
        <item:update>
          <href type="string"><![CDATA[http://test.com/items/id3]]></href>
          <method type="string"><![CDATA[PUT]]></method>
        </item:update>
        <item:delete>
          <href type="string"><![CDATA[http://test.com/items/id3]]></href>
          <method type="string"><![CDATA[DELETE]]></method>
        </item:delete>
      </_links>
    </item>
  </items>
</_embedded>
<_links>
  <self>
    <href type="string"><![CDATA[http://test.com/items/?page=1&perPage=10&sort=name&order=asc]]></href>
    <method type="string"><![CDATA[GET]]></method>
  </self>
  <item:create>
    <href type="string"><![CDATA[http://test.com/items/]]></href>
    <method type="string"><![CDATA[POST]]></method>
  </item:create>
</_links>
EOD;

        self::assertEquals($expectedXml, $xml);
    }
}
