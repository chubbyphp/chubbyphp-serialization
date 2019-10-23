<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization\Unit\Encoder;

use Chubbyphp\Serialization\Encoder\XmlTypeEncoder;

/**
 * @covers \Chubbyphp\Serialization\Encoder\XmlTypeEncoder
 *
 * @internal
 */
final class XmlTypeEncoderTest extends AbstractTypeEncoderTest
{
    public function testContentType(): void
    {
        $transformer = new XmlTypeEncoder();

        self::assertSame('application/xml', $transformer->getContentType());
    }

    /**
     * @dataProvider dataProvider
     *
     * @param array $data
     */
    public function testFormat(array $data): void
    {
        $xmlTransformer = new XmlTypeEncoder(true);
        $xml = $xmlTransformer->encode($data);

        $expectedXml = <<<'EOT'
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
        <name type="string">A fäncy Name
</name>
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
            <templated type="boolean">false</templated>
            <rels></rels>
            <attributes>
              <method type="string">GET</method>
            </attributes>
          </read>
          <update>
            <href type="string">http://test.com/items/id1</href>
            <templated type="boolean">false</templated>
            <rels></rels>
            <attributes>
              <method type="string">PUT</method>
            </attributes>
          </update>
          <delete>
            <href type="string">http://test.com/items/id1</href>
            <templated type="boolean">false</templated>
            <rels></rels>
            <attributes>
              <method type="string">DELETE</method>
            </attributes>
          </delete>
        </meta-links>
      </object>
    </mainItem>
    <items>
      <object type="item" key="0">
        <id type="string">id1</id>
        <name type="string">A fäncy Name
</name>
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
            <templated type="boolean">false</templated>
            <rels></rels>
            <attributes>
              <method type="string">GET</method>
            </attributes>
          </read>
          <update>
            <href type="string">http://test.com/items/id1</href>
            <templated type="boolean">false</templated>
            <rels></rels>
            <attributes>
              <method type="string">PUT</method>
            </attributes>
          </update>
          <delete>
            <href type="string">http://test.com/items/id1</href>
            <templated type="boolean">false</templated>
            <rels></rels>
            <attributes>
              <method type="string">DELETE</method>
            </attributes>
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
            <templated type="boolean">false</templated>
            <rels></rels>
            <attributes>
              <method type="string">GET</method>
            </attributes>
          </read>
          <update>
            <href type="string">http://test.com/items/id2</href>
            <templated type="boolean">false</templated>
            <rels></rels>
            <attributes>
              <method type="string">PUT</method>
            </attributes>
          </update>
          <delete>
            <href type="string">http://test.com/items/id2</href>
            <templated type="boolean">false</templated>
            <rels></rels>
            <attributes>
              <method type="string">DELETE</method>
            </attributes>
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
        <progress type="float">100.0</progress>
        <active type="boolean">false</active>
        <meta-links>
          <read>
            <href type="string">http://test.com/items/id3</href>
            <templated type="boolean">false</templated>
            <rels></rels>
            <attributes>
              <method type="string">GET</method>
            </attributes>
          </read>
          <update>
            <href type="string">http://test.com/items/id3</href>
            <templated type="boolean">false</templated>
            <rels></rels>
            <attributes>
              <method type="string">PUT</method>
            </attributes>
          </update>
          <delete>
            <href type="string">http://test.com/items/id3</href>
            <templated type="boolean">false</templated>
            <rels></rels>
            <attributes>
              <method type="string">DELETE</method>
            </attributes>
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
EOT;

        self::assertEquals($expectedXml, $xml);
    }

    public function testFormatFormatWithoutType(): void
    {
        $expectedXml = <<<'EOT'
<?xml version="1.0" encoding="UTF-8"?>
<object>
  <key type="string">value</key>
</object>
EOT;

        $xmlTransformer = new XmlTypeEncoder(true);

        self::assertSame($expectedXml, $xmlTransformer->encode(['key' => 'value']));
    }

    public function testInvalidValueAsString(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Unsupported data type: object');

        $xmlTransformer = new XmlTypeEncoder(true);
        $xmlTransformer->encode(['key' => new \stdClass()]);
    }
}
