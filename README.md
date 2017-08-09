# chubbyphp-serialization

[![Build Status](https://api.travis-ci.org/chubbyphp/chubbyphp-serialization.png?branch=master)](https://travis-ci.org/chubbyphp/chubbyphp-serialization)
[![Total Downloads](https://poser.pugx.org/chubbyphp/chubbyphp-serialization/downloads.png)](https://packagist.org/packages/chubbyphp/chubbyphp-serialization)
[![Latest Stable Version](https://poser.pugx.org/chubbyphp/chubbyphp-serialization/v/stable.png)](https://packagist.org/packages/chubbyphp/chubbyphp-serialization)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/chubbyphp/chubbyphp-serialization/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/chubbyphp/chubbyphp-serialization/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/chubbyphp/chubbyphp-serialization/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/chubbyphp/chubbyphp-serialization/?branch=master)

## Description

A simple serialization.

## Requirements

 * php: ~7.0
 * psr/log: ~1.0
 * psr/http-message: ~1.0

## Suggest

 * container-interop/container-interop: ~1.0
 * pimple/pimple: ~3.0

## Installation

Through [Composer](http://getcomposer.org) as [chubbyphp/chubbyphp-serialization][1].

```sh
composer require chubbyphp/chubbyphp-serialization "~1.0@beta"
```

## Usage

### Accessor

 * [MethodAccessor][2]
 * [PropertyAccessor][3]

### Link

 * [Link][4]

### Mapping

```php
<?php

namespace MyProject\Serialization;

use Chubbyphp\Serialization\Mapping\ObjectMappingInterface;
use Chubbyphp\Serialization\Mapping\Field\FieldMapping;
use Chubbyphp\Serialization\Mapping\Field\FieldMappingInterface;
use Chubbyphp\Serialization\Mapping\Link\LinkMapping;
use Chubbyphp\Serialization\Mapping\Link\LinkMappingInterface;
use Chubbyphp\Serialization\Serializer\Field\CollectionFieldSerializer;
use Chubbyphp\Serialization\Serializer\Field\ObjectFieldSerializer;
use MyProject\Model\Model;

class ModelMapping implements ObjectMappingInterface
{
    /**
     * @return string
     */
    public function getClass(): string
    {
        return Model::class;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return 'model';
    }

    /**
     * @return FieldMappingInterface[]
     */
    public function getFieldMappings(): array
    {
        return [
            new FieldMapping('name'),
        ];
    }

    /**
     * @return getEmbeddedFieldMappings[]
     */
    public function getFieldMappings(): array
    {
        return [
            new FieldMapping('user', new ObjectFieldSerializer(new MethodAccessor('getUser'))),
            new FieldMapping('posts', new CollectionFieldSerializer(new PropertyAccessor('getPosts'))),
        ];
    }

    /**
     * @return LinkMappingInterface[]
     */
    public function getLinkMappings(): array
    {
        return [
            new LinkMapping('read', new CallbackLinkSerializer(function (Request $request, Model $model) {
                return new Link('http://test.com/models/'.$model->getId(), Link::METHOD_GET);
            })),
            new LinkMapping('update', new CallbackLinkSerializer(function (Request $request, Model $model) {
                return new Link('http://test.com/models/'.$model->getId(), Link::METHOD_PUT);
            })),
            new LinkMapping('update', new CallbackLinkSerializer(function (Request $request, Model $model) {
                return new Link('http://test.com/models/'.$model->getId(), Link::METHOD_DELETE);
            })),
        ];
    }
}
```

 * [FieldMapping][5]
 * [LazyObjectMapping][6]
 * [LinkMapping][7]

### Provider

 * [SerializationProvider][8]

### Registry

 * [ObjectMappingRegistry][9]

### Serializer

```php
<?php

use Chubbyphp\Serialization\Registry\ObjectMappingRegistry;
use Chubbyphp\Serialization\Serializer;
use Chubbyphp\Serialization\Transformer\JsonTransformer;
use MyProject\Model\Model;
use MyProject\Serialization\ModelMapping;

$request = ...; // PSR7 Request

$search = new Search();
$search
    ->setPage(1)
    ->setPerPage(10)
    ->setSort('name')
    ->setOrder('asc')
    ->setItems([
        (new Item('id1'))
            ->setName('A fancy Name')
            ->setTreeValues([1 => [2 => 3]])
            ->setProgress(76.8)->setActive(true),
        (new Item('id2'))
            ->setName('B fancy Name')
            ->setTreeValues([1 => [2 => 3, 3 => 4]])
            ->setProgress(24.7)
            ->setActive(true),
        (new Item('id3'))
            ->setName('C fancy Name')
            ->setTreeValues([1 => [2 => 3, 3 => 4, 6 => 7]])
            ->setProgress(100)
            ->setActive(false),
    ]);

$serializer = new Serializer(new ObjectMappingRegistry([new ModelMapping()]));
$data = $serializer->serialize($request, $search);

$transformer = new JsonTransformer();
$json = $transformer->transform($data);
```

#### Field

* [CallbackFieldSerializer][10]
* [CollectionFieldSerializer][11]
* [ObjectFieldSerializer][12]
* [ValueFieldSerializer][13]

#### Link

* [CallbackLinkSerializer][14]

### Transformer

* [JsonTransformer][15]
* [UrlEncodedTransformer][16]
* [XmlTransformer][17]
* [YamlTransformer][18]

## Copyright

Dominik Zogg 2017

[1]: https://packagist.org/packages/chubbyphp/chubbyphp-serialization

[2]: doc/Accessor/MethodAccessor.md
[3]: doc/Accessor/PropertyAccessor.md

[4]: doc/Link/Link.md

[5]: doc/Mapping/FieldMapping.md
[6]: doc/Mapping/LazyObjectMapping.md
[7]: doc/Mapping/LinkMapping.md

[8]: doc/Provider/SerializationProvider.md

[9]: doc/Registry/ObjectMappingRegistry.md

[10]: doc/Serializer/Field/CallbackFieldSerializer.md
[11]: doc/Serializer/Field/CollectionFieldSerializer.md
[12]: doc/Serializer/Field/ObjectFieldSerializer.md
[13]: doc/Serializer/Field/ValueFieldSerializer.md
[14]: doc/Serializer/Link/CallbackLinkSerializer.md

[15]: doc/Transformer/JsonTransformer.md
[16]: doc/Transformer/UrlEncodedTransformer.md
[17]: doc/Transformer/XmlTransformer.md
[18]: doc/Transformer/YamlTransformer.md
