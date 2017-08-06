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
<meta-type value="search">
  <page type="integer">1</page>
  <perPage type="integer">10</perPage>
  <search></search>
  <sort type="string">name</sort>
  <order type="string">asc</order>
  <meta-embedded>
    <mainItem>
      <meta-type value="item">
        <id type="string">id1</id>
        <name type="string">A fancy Name</name>
        <treeValues>
          <treeValue key="1">
            <treeValue type="integer" key="2">3</treeValue>
          </treeValue>
        </treeValues>
        <progress type="float">76.8</progress>
        <active type="boolean">true</active>
        <meta-links>
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
        </meta-links>
      </meta-type>
    </mainItem>
    <items>
      <meta-type value="item" key="0">
        <id type="string">id1</id>
        <name type="string">A fancy Name</name>
        <treeValues>
          <treeValue key="1">
            <treeValue type="integer" key="2">3</treeValue>
          </treeValue>
        </treeValues>
        <progress type="float">76.8</progress>
        <active type="boolean">true</active>
        <meta-links>
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
        </meta-links>
      </meta-type>
      <meta-type value="item" key="1">
        <id type="string">id2</id>
        <name type="string">B fancy Name</name>
        <treeValues>
          <treeValue key="1">
            <treeValue type="integer" key="2">3</treeValue>
            <treeValue type="integer" key="3">4</treeValue>
          </treeValue>
        </treeValues>
        <progress type="float">24.7</progress>
        <active type="boolean">true</active>
        <meta-links>
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
        </meta-links>
      </meta-type>
      <meta-type value="item" key="2">
        <id type="string">id3</id>
        <name type="string">C fancy Name</name>
        <treeValues>
          <treeValue key="1">
            <treeValue type="integer" key="2">3</treeValue>
            <treeValue type="integer" key="3">4</treeValue>
            <treeValue type="integer" key="6">7</treeValue>
          </treeValue>
        </treeValues>
        <progress type="float">100</progress>
        <active type="boolean">false</active>
        <meta-links>
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
        </meta-links>
      </meta-type>
    </items>
  </meta-embedded>
  <meta-links>
    <search>
      <href type="string"><![CDATA[http://test.com/items/?page=1&perPage=10&sort=name&order=asc]]></href>
      <method type="string">GET</method>
    </search>
    <create>
      <href type="string">http://test.com/items/</href>
      <method type="string">POST</method>
    </create>
  </meta-links>
</meta-type>
EOD;

        self::assertEquals($expectedXml, $xml);
    }

    public function testFormatFormatWithoutType()
    {
        self::expectException(\InvalidArgumentException::class);
        self::expectExceptionMessage('Data missing _type');

        $xmlTransformer = new XmlTransformer(true);
        $xmlTransformer->transform([]);
    }

    public function testInvalidValueAsString()
    {
        self::expectException(\InvalidArgumentException::class);
        self::expectExceptionMessage('Unsupported data type: object');

        $xmlTransformer = new XmlTransformer(true);
        $xmlTransformer->transform(['key' => new \stdClass(), '_type' => 'test']);
    }
}
