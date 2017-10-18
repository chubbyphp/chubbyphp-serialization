<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization\Encoder;

use Chubbyphp\Serialization\Encoder\XmlTypeEncoder;

/**
 * @covers \Chubbyphp\Serialization\Encoder\XmlTypeEncoder
 */
class XmlTypeEncoderTest extends AbstractTypeEncoderTest
{
    public function testContentType()
    {
        $transformer = new XmlTypeEncoder();

        self::assertSame('application/xml', $transformer->getContentType());
    }

    /**
     * @dataProvider dataProvider
     *
     * @param array $data
     */
    public function testFormat(array $data)
    {
        $xmlTransformer = new XmlTypeEncoder(true);
        $xml = $xmlTransformer->encode($data);

        $expectedXml = <<<EOD
<?xml version="1.0" encoding="UTF-8"?>
<object type="search">
  <page type="integer">1</page>
  <perPage type="integer">10</perPage>
  <search></search>
  <sort type="string">name</sort>
  <order type="string">asc</order>
  <meta-embedded>
    <mainItem>
      <object type="item">
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
      </object>
    </mainItem>
    <items>
      <object type="item" key="0">
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
      </object>
      <object type="item" key="1">
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
      </object>
      <object type="item" key="2">
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
      </object>
    </items>
  </meta-embedded>
  <meta-links>
    <self>
      <href type="string"><![CDATA[http://test.com/items/?page=1&perPage=10&sort=name&order=asc]]></href>
      <method type="string">GET</method>
    </self>
    <create>
      <href type="string">http://test.com/items/</href>
      <method type="string">POST</method>
    </create>
  </meta-links>
</object>
EOD;

        self::assertEquals($expectedXml, $xml);
    }

    public function testFormatFormatWithoutType()
    {
        self::expectException(\InvalidArgumentException::class);
        self::expectExceptionMessage('Data missing _type');

        $xmlTransformer = new XmlTypeEncoder(true);
        $xmlTransformer->encode([]);
    }

    public function testInvalidValueAsString()
    {
        self::expectException(\InvalidArgumentException::class);
        self::expectExceptionMessage('Unsupported data type: object');

        $xmlTransformer = new XmlTypeEncoder(true);
        $xmlTransformer->encode(['key' => new \stdClass(), '_type' => 'test']);
    }
}
