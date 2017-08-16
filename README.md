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
composer require chubbyphp/chubbyphp-serialization "~1.0"
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
            new FieldMapping('reference', new ObjectFieldSerializer(new MethodAccessor('getReference'))),
            new FieldMapping('collection', new CollectionFieldSerializer(new PropertyAccessor('getCollection'))),
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

$model = new Model();
$model
    ->setName('name')
    ->setReference(...)
    ->setCollection([
        ...
    ]);

$serializer = new Serializer(new ObjectMappingRegistry([new ModelMapping()]));
$data = $serializer->serialize($request, $model);

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

```php
<?php

use Chubbyphp\Serialization\Transformer;
use Chubbyphp\Serialization\Transformer\JsonTransformer;
use Chubbyphp\Serialization\Transformer\UrlEncodedTransformer;
use Chubbyphp\Serialization\Transformer\XmlTransformer;
use Chubbyphp\Serialization\Transformer\YamlTransformer;

$transformer = new Transformer([
    new JsonTransformer(),
    new UrlEncodedTransformer(),
    new XmlTransformer(),
    new YamlTransformer(),
]);

$contentTypes = $transformer->getContentTypes();

$data = $transformer->transform(['key' => 'value'], 'application/json');
```

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
