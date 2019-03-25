<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization\Encoder;

use Chubbyphp\Serialization\Encoder\JsonxTypeEncoder;

/**
 * @covers \Chubbyphp\Serialization\Encoder\JsonxTypeEncoder
 */
class JsonxTypeEncoderTest extends AbstractTypeEncoderTest
{
    public function testContentType()
    {
        $transformer = new JsonxTypeEncoder();

        self::assertSame('application/x-jsonx', $transformer->getContentType());
    }

    /**
     * @dataProvider dataProvider
     *
     * @param array $data
     */
    public function testFormat(array $data)
    {
        $transformer = new JsonxTypeEncoder(true);

        $jsonx = $transformer->encode($data);

        $expectedJsonx = <<<'EOT'
<?xml version="1.0" encoding="UTF-8"?>
<json:object xsi:schemaLocation="http://www.datapower.com/schemas/json jsonx.xsd" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:json="http://www.ibm.com/xmlns/prod/2009/jsonx">
  <json:number name="page">1</json:number>
  <json:number name="perPage">10</json:number>
  <json:null name="search"/>
  <json:string name="sort">name</json:string>
  <json:string name="order">asc</json:string>
  <json:object name="_embedded">
    <json:object name="mainItem">
      <json:string name="id">id1</json:string>
      <json:string name="name">A fäncy Name
</json:string>
      <json:object name="treeValues">
        <json:object name="1">
          <json:number name="2">3</json:number>
        </json:object>
      </json:object>
      <json:number name="progress">76.8</json:number>
      <json:boolean name="active">true</json:boolean>
      <json:string name="_type">item</json:string>
      <json:object name="_links">
        <json:object name="read">
          <json:string name="href">http://test.com/items/id1</json:string>
          <json:boolean name="templated">false</json:boolean>
          <json:array name="rels"/>
          <json:object name="attributes">
            <json:string name="method">GET</json:string>
          </json:object>
        </json:object>
        <json:object name="update">
          <json:string name="href">http://test.com/items/id1</json:string>
          <json:boolean name="templated">false</json:boolean>
          <json:array name="rels"/>
          <json:object name="attributes">
            <json:string name="method">PUT</json:string>
          </json:object>
        </json:object>
        <json:object name="delete">
          <json:string name="href">http://test.com/items/id1</json:string>
          <json:boolean name="templated">false</json:boolean>
          <json:array name="rels"/>
          <json:object name="attributes">
            <json:string name="method">DELETE</json:string>
          </json:object>
        </json:object>
      </json:object>
    </json:object>
    <json:array name="items">
      <json:object>
        <json:string name="id">id1</json:string>
        <json:string name="name">A fäncy Name
</json:string>
        <json:object name="treeValues">
          <json:object name="1">
            <json:number name="2">3</json:number>
          </json:object>
        </json:object>
        <json:number name="progress">76.8</json:number>
        <json:boolean name="active">true</json:boolean>
        <json:string name="_type">item</json:string>
        <json:object name="_links">
          <json:object name="read">
            <json:string name="href">http://test.com/items/id1</json:string>
            <json:boolean name="templated">false</json:boolean>
            <json:array name="rels"/>
            <json:object name="attributes">
              <json:string name="method">GET</json:string>
            </json:object>
          </json:object>
          <json:object name="update">
            <json:string name="href">http://test.com/items/id1</json:string>
            <json:boolean name="templated">false</json:boolean>
            <json:array name="rels"/>
            <json:object name="attributes">
              <json:string name="method">PUT</json:string>
            </json:object>
          </json:object>
          <json:object name="delete">
            <json:string name="href">http://test.com/items/id1</json:string>
            <json:boolean name="templated">false</json:boolean>
            <json:array name="rels"/>
            <json:object name="attributes">
              <json:string name="method">DELETE</json:string>
            </json:object>
          </json:object>
        </json:object>
      </json:object>
      <json:object>
        <json:string name="id">id2</json:string>
        <json:string name="name">B fancy Name</json:string>
        <json:object name="treeValues">
          <json:object name="1">
            <json:number name="2">3</json:number>
            <json:number name="3">4</json:number>
          </json:object>
        </json:object>
        <json:number name="progress">24.7</json:number>
        <json:boolean name="active">true</json:boolean>
        <json:string name="_type">item</json:string>
        <json:object name="_links">
          <json:object name="read">
            <json:string name="href">http://test.com/items/id2</json:string>
            <json:boolean name="templated">false</json:boolean>
            <json:array name="rels"/>
            <json:object name="attributes">
              <json:string name="method">GET</json:string>
            </json:object>
          </json:object>
          <json:object name="update">
            <json:string name="href">http://test.com/items/id2</json:string>
            <json:boolean name="templated">false</json:boolean>
            <json:array name="rels"/>
            <json:object name="attributes">
              <json:string name="method">PUT</json:string>
            </json:object>
          </json:object>
          <json:object name="delete">
            <json:string name="href">http://test.com/items/id2</json:string>
            <json:boolean name="templated">false</json:boolean>
            <json:array name="rels"/>
            <json:object name="attributes">
              <json:string name="method">DELETE</json:string>
            </json:object>
          </json:object>
        </json:object>
      </json:object>
      <json:object>
        <json:string name="id">id3</json:string>
        <json:string name="name">C fancy Name</json:string>
        <json:object name="treeValues">
          <json:object name="1">
            <json:number name="2">3</json:number>
            <json:number name="3">4</json:number>
            <json:number name="6">7</json:number>
          </json:object>
        </json:object>
        <json:number name="progress">100</json:number>
        <json:boolean name="active">false</json:boolean>
        <json:string name="_type">item</json:string>
        <json:object name="_links">
          <json:object name="read">
            <json:string name="href">http://test.com/items/id3</json:string>
            <json:boolean name="templated">false</json:boolean>
            <json:array name="rels"/>
            <json:object name="attributes">
              <json:string name="method">GET</json:string>
            </json:object>
          </json:object>
          <json:object name="update">
            <json:string name="href">http://test.com/items/id3</json:string>
            <json:boolean name="templated">false</json:boolean>
            <json:array name="rels"/>
            <json:object name="attributes">
              <json:string name="method">PUT</json:string>
            </json:object>
          </json:object>
          <json:object name="delete">
            <json:string name="href">http://test.com/items/id3</json:string>
            <json:boolean name="templated">false</json:boolean>
            <json:array name="rels"/>
            <json:object name="attributes">
              <json:string name="method">DELETE</json:string>
            </json:object>
          </json:object>
        </json:object>
      </json:object>
    </json:array>
  </json:object>
  <json:object name="_links">
    <json:object name="self">
      <json:string name="href">http://test.com/items/?page=1&amp;perPage=10&amp;sort=name&amp;order=asc</json:string>
      <json:string name="method">GET</json:string>
    </json:object>
    <json:object name="create">
      <json:string name="href">http://test.com/items/</json:string>
      <json:string name="method">POST</json:string>
    </json:object>
  </json:object>
  <json:string name="_type">search</json:string>
</json:object>
EOT;
        self::assertEquals($expectedJsonx, $jsonx);
    }

    public function testArray()
    {
        $transformer = new JsonxTypeEncoder(true);

        $expectedJsonx = <<<'EOT'
<?xml version="1.0" encoding="UTF-8"?>
<json:array xsi:schemaLocation="http://www.datapower.com/schemas/json jsonx.xsd" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:json="http://www.ibm.com/xmlns/prod/2009/jsonx">
  <json:object>
    <json:string name="key">value</json:string>
  </json:object>
  <json:array>
    <json:string>value</json:string>
  </json:array>
  <json:boolean>false</json:boolean>
  <json:string>text</json:string>
  <json:number>1</json:number>
  <json:number>1.1</json:number>
  <json:null/>
</json:array>
EOT;

        $jsonx = $transformer->encode([
            ['key' => 'value'],
            ['value'],
            false,
            'text',
            1,
            1.1,
            null,
        ]);

        self::assertEquals($expectedJsonx, $jsonx);
    }

    public function testWithInvalidDataType()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Value needs to be of type array|bool|string|int|float|null, DateTime given');

        $transformer = new JsonxTypeEncoder(true);
        $transformer->encode(['key' => new \DateTime()]);
    }
}
