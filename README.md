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
composer require chubbyphp/chubbyphp-serialization "~1.1"
```

## Usage

### Accessor

 * [MethodAccessor][2]
 * [PropertyAccessor][3]

### Link

 * [Link][4]
 * [NullLink][5]

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

 * [FieldMapping][6]
 * [LazyObjectMapping][7]
 * [LinkMapping][8]

### Provider

 * [SerializationProvider][9]

### Registry

 * [ObjectMappingRegistry][10]

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

* [CallbackFieldSerializer][11]
* [CollectionFieldSerializer][12]
* [ObjectFieldSerializer][13]
* [ValueFieldSerializer][14]

#### Link

* [CallbackLinkSerializer][15]

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

* [JsonTransformer][16]
* [UrlEncodedTransformer][17]
* [XmlTransformer][18]
* [YamlTransformer][19]

## Copyright

Dominik Zogg 2017

[1]: https://packagist.org/packages/chubbyphp/chubbyphp-serialization

[2]: doc/Accessor/MethodAccessor.md
[3]: doc/Accessor/PropertyAccessor.md

[4]: doc/Link/Link.md
[5]: doc/Link/NullLink.md

[6]: doc/Mapping/FieldMapping.md
[7]: doc/Mapping/LazyObjectMapping.md
[8]: doc/Mapping/LinkMapping.md

[9]: doc/Provider/SerializationProvider.md

[10]: doc/Registry/ObjectMappingRegistry.md

[11]: doc/Serializer/Field/CallbackFieldSerializer.md
[12]: doc/Serializer/Field/CollectionFieldSerializer.md
[13]: doc/Serializer/Field/ObjectFieldSerializer.md
[14]: doc/Serializer/Field/ValueFieldSerializer.md
[15]: doc/Serializer/Link/CallbackLinkSerializer.md

[16]: doc/Transformer/JsonTransformer.md
[17]: doc/Transformer/UrlEncodedTransformer.md
[18]: doc/Transformer/XmlTransformer.md
[19]: doc/Transformer/YamlTransformer.md
