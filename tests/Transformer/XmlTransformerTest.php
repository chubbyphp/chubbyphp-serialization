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
<search>
  <page type="integer">1</page>
  <perPage type="integer">10</perPage>
  <search></search>
  <sort type="string">name</sort>
  <order type="string">asc</order>
  <_embedded>
    <items>
      <item>
        <id type="string">id1</id>
        <name type="string">A fancy Name</name>
        <progress type="float">76.8</progress>
        <active type="boolean">true</active>
        <_links>
          <read>
            <href type="string">http://test.com/items/id1</href>
            <method type="string">GET</method>
          </read>
          <update>
            <href type="string">http://test.com/items/id1</href>
            <method type="string">PUT</method>
          </update>
          <delete>
            <href type="string">http://test.com/items/id1</href>
            <method type="string">DELETE</method>
          </delete>
        </_links>
      </item>
      <item>
        <id type="string">id2</id>
        <name type="string">B fancy Name</name>
        <progress type="float">24.7</progress>
        <active type="boolean">true</active>
        <_links>
          <read>
            <href type="string">http://test.com/items/id2</href>
            <method type="string">GET</method>
          </read>
          <update>
            <href type="string">http://test.com/items/id2</href>
            <method type="string">PUT</method>
          </update>
          <delete>
            <href type="string">http://test.com/items/id2</href>
            <method type="string">DELETE</method>
          </delete>
        </_links>
      </item>
      <item>
        <id type="string">id3</id>
        <name type="string">C fancy Name</name>
        <progress type="float">100</progress>
        <active type="boolean">false</active>
        <_links>
          <read>
            <href type="string">http://test.com/items/id3</href>
            <method type="string">GET</method>
          </read>
          <update>
            <href type="string">http://test.com/items/id3</href>
            <method type="string">PUT</method>
          </update>
          <delete>
            <href type="string">http://test.com/items/id3</href>
            <method type="string">DELETE</method>
          </delete>
        </_links>
      </item>
    </items>
  </_embedded>
  <_links>
    <search>
      <href type="string"><![CDATA[http://test.com/items/?page=1&perPage=10&sort=name&order=asc]]></href>
      <method type="string">GET</method>
    </search>
    <create>
      <href type="string">http://test.com/items/</href>
      <method type="string">POST</method>
    </create>
  </_links>
</search>
EOD;

        self::assertEquals($expectedXml, $xml);
    }
}
