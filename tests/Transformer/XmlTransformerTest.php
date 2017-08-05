<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization\Transformer;

use Chubbyphp\Serialization\Transformer\XmlTransformer;

/**
 * @covers \Chubbyphp\Serialization\Transformer\XmlTransformer
 */
class XmlTransformerTest extends AbstractTransformerTest
{
    /**
     * @dataProvider dataProvider
     *
     * @param array $data
     */
    public function testFormat(array $data)
    {
        $xmlTransformer = new XmlTransformer(true);
        $xml = $xmlTransformer->transform($data);

        $expectedXml = <<<EOD
<?xml version="1.0" encoding="UTF-8"?>
<response>
  <page type="integer">1</page>
  <per_page type="integer">10</per_page>
  <search></search>
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
          <read>
            <href type="string"><![CDATA[http://test.com/items/id1]]></href>
            <method type="string"><![CDATA[GET]]></method>
          </read>
          <update>
            <href type="string"><![CDATA[http://test.com/items/id1]]></href>
            <method type="string"><![CDATA[PUT]]></method>
          </update>
          <delete>
            <href type="string"><![CDATA[http://test.com/items/id1]]></href>
            <method type="string"><![CDATA[DELETE]]></method>
          </delete>
        </_links>
      </item>
      <item>
        <id type="string"><![CDATA[id2]]></id>
        <name type="string"><![CDATA[B fancy Name]]></name>
        <progress type="float">24</progress>
        <active type="boolean">true</active>
        <_links>
          <read>
            <href type="string"><![CDATA[http://test.com/items/id2]]></href>
            <method type="string"><![CDATA[GET]]></method>
          </read>
          <update>
            <href type="string"><![CDATA[http://test.com/items/id2]]></href>
            <method type="string"><![CDATA[PUT]]></method>
          </update>
          <delete>
            <href type="string"><![CDATA[http://test.com/items/id2]]></href>
            <method type="string"><![CDATA[DELETE]]></method>
          </delete>
        </_links>
      </item>
      <item>
        <id type="string"><![CDATA[id3]]></id>
        <name type="string"><![CDATA[C fancy Name]]></name>
        <progress type="float">100</progress>
        <active type="boolean">false</active>
        <_links>
          <read>
            <href type="string"><![CDATA[http://test.com/items/id3]]></href>
            <method type="string"><![CDATA[GET]]></method>
          </read>
          <update>
            <href type="string"><![CDATA[http://test.com/items/id3]]></href>
            <method type="string"><![CDATA[PUT]]></method>
          </update>
          <delete>
            <href type="string"><![CDATA[http://test.com/items/id3]]></href>
            <method type="string"><![CDATA[DELETE]]></method>
          </delete>
        </_links>
      </item>
    </items>
  </_embedded>
  <_links>
    <self>
      <href type="string"><![CDATA[http://test.com/items/?page=1&per_page=10&sort=name&order=asc]]></href>
      <method type="string"><![CDATA[GET]]></method>
    </self>
    <create>
      <href type="string"><![CDATA[http://test.com/items/]]></href>
      <method type="string"><![CDATA[POST]]></method>
    </create>
  </_links>
</response>
EOD;

        self::assertEquals($expectedXml, $xml);
    }
}
