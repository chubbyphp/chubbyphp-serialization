# chubbyphp-serialization

[![Build Status](https://api.travis-ci.org/chubbyphp/chubbyphp-serialization.png?branch=master)](https://travis-ci.org/chubbyphp/chubbyphp-serialization)
[![Coverage Status](https://coveralls.io/repos/github/chubbyphp/chubbyphp-serialization/badge.svg?branch=master)](https://coveralls.io/github/chubbyphp/chubbyphp-serialization?branch=master)
[![Total Downloads](https://poser.pugx.org/chubbyphp/chubbyphp-serialization/downloads.png)](https://packagist.org/packages/chubbyphp/chubbyphp-serialization)
[![Monthly Downloads](https://poser.pugx.org/chubbyphp/chubbyphp-serialization/d/monthly)](https://packagist.org/packages/chubbyphp/chubbyphp-serialization)
[![Latest Stable Version](https://poser.pugx.org/chubbyphp/chubbyphp-serialization/v/stable.png)](https://packagist.org/packages/chubbyphp/chubbyphp-serialization)
[![Latest Unstable Version](https://poser.pugx.org/chubbyphp/chubbyphp-serialization/v/unstable)](https://packagist.org/packages/chubbyphp/chubbyphp-serialization)

## Description

A simple serialization.

## Requirements

 * php: ^7.2
 * doctrine/inflector: ^1.0
 * psr/http-message: ^1.0.1
 * psr/link: ^1.0
 * psr/log: ^1.1

## Suggest

 * chubbyphp/chubbyphp-container: ^1.0
 * pimple/pimple: ^3.2.3
 * psr/container: ^1.0
 * symfony/dependency-injection: ^2.8|^3.4|^4.2|^5.0 (symfony integration)
 * symfony/yaml: ^2.8|^3.4|^4.2|^5.0 (application/x-yaml support)

## Installation

Through [Composer](http://getcomposer.org) as [chubbyphp/chubbyphp-serialization][1].

```sh
composer require chubbyphp/chubbyphp-serialization "^2.12"
```

## Usage

### Accessor

 * [MethodAccessor][2]
 * [PropertyAccessor][3]

### Encoder

 * [Encoder][4]

#### Type Encoder

 * [JsonTypeEncoder][5]
 * [JsonxTypeEncoder][6]
 * [UrlEncodedTypeEncoder][7]
 * [XmlTypeEncoder][8]
 * [YamlTypeEncoder][9]

### Link

 * [Link][10]
 * [LinkBuilder][11]

### Normalizer

 * [Normalizer][12]

#### Field Normalizer

 * [CallbackFieldNormalizer][13]
 * [DateTimeFieldNormalizer][14]
 * [FieldNormalizer][15]

##### Relation Field Normalizer

 * [EmbedManyFieldNormalizer][16]
 * [EmbedOneFieldNormalizer][17]
 * [ReferenceManyFieldNormalizer][18]
 * [ReferenceOneFieldNormalizer][19]

#### Link Normalizer

 * [CallbackLinkNormalizer][20]

#### Normalizer Context

 * [NormalizerContext][21]
 * [NormalizerContextBuilder][22]

### NormalizerObjectMappingRegistry

* [NormalizerObjectMappingRegistry][23]

### Mapping

#### NormalizationFieldMapping

 * [NormalizationFieldMapping][24]
 * [NormalizationFieldMappingBuilder][25]

#### NormalizationLinkMapping

 * [NormalizationLinkMapping][26]
 * [NormalizationLinkMappingBuilder][27]

#### NormalizationObjectMapping

 * [AdvancecNormalizationObjectMapping][28]
 * [SimpleNormalizationObjectMapping][29]

#### LazyNormalizationObjectMapping

 * [CallableNormalizationObjectMapping][30]
 * [LazyNormalizationObjectMapping][31]

### ServiceFactory

* [SerializationServiceFactory][32]

### ServiceProvider

* [SerializationServiceProvider][33]

### Serializer

```php
<?php

use Chubbyphp\Serialization\Encoder\Encoder;
use Chubbyphp\Serialization\Encoder\JsonTypeEncoder;
use Chubbyphp\Serialization\Encoder\JsonxTypeEncoder;
use Chubbyphp\Serialization\Encoder\UrlEncodedTypeEncoder;
use Chubbyphp\Serialization\Encoder\XmlTypeEncoder;
use Chubbyphp\Serialization\Encoder\YamlTypeEncoder;
use Chubbyphp\Serialization\Normalizer\Normalizer;
use Chubbyphp\Serialization\Normalizer\NormalizerObjectMappingRegistry;
use Chubbyphp\Serialization\Serializer;
use MyProject\Serialization\ModelMapping;
use MyProject\Model\Model;
use Psr\Http\Message\ServerRequestInterface;

$logger = ...;

$serializer = new Serializer(
    new Normalizer(
        new NormalizerObjectMappingRegistry([
            new ModelMapping()
        ]),
        $logger
    ),
    new Encoder([
        new JsonTypeEncoder(),
        new JsonxTypeEncoder(),
        new UrlEncodedTypeEncoder(),
        new XmlTypeEncoder(),
        new YamlTypeEncoder()
    ])
);

$model = new Model;
$model->setName('php');

$json = $serializer->serialize(
    $model,
    'application/json'
);

echo $json;
// '{"name": "php"}'

$model = new Model;
$model->setName('php');

$data = $serializer->normalize(
    $model
);

print_r($data);
// ['name' => 'php']

print_r($serializer->getContentTypes());
//[
//    'application/json',
//    'application/x-jsonx',
//    'application/x-www-form-urlencoded',
//    'application/xml',
//    'application/x-yaml'
//]

echo $serializer->encode(
    ['name' => 'php'],
    'application/json'
);
// '{"name": "php"}'
```

## Copyright

Dominik Zogg 2019


[1]: https://packagist.org/packages/chubbyphp/chubbyphp-serialization

[2]: doc/Accessor/MethodAccessor.md
[3]: doc/Accessor/PropertyAccessor.md

[4]: doc/Encoder/Encoder.md

[5]: doc/Encoder/JsonTypeEncoder.md
[6]: doc/Encoder/JsonxTypeEncoder.md
[7]: doc/Encoder/UrlEncodedTypeEncoder.md
[8]: doc/Encoder/XmlTypeEncoder.md
[9]: doc/Encoder/YamlTypeEncoder.md

[10]: doc/Link/Link.md
[11]: doc/Link/LinkBuilder.md

[12]: doc/Normalizer/Normalizer.md

[13]: doc/Normalizer/CallbackFieldNormalizer.md
[14]: doc/Normalizer/DateTimeFieldNormalizer.md
[15]: doc/Normalizer/FieldNormalizer.md

[16]: doc/Normalizer/Relation/EmbedManyFieldNormalizer.md
[17]: doc/Normalizer/Relation/EmbedOneFieldNormalizer.md
[18]: doc/Normalizer/Relation/ReferenceManyFieldNormalizer.md
[19]: doc/Normalizer/Relation/ReferenceOneFieldNormalizer.md

[20]: doc/Normalizer/CallbackLinkNormalizer.md

[21]: doc/Normalizer/NormalizerContext.md
[22]: doc/Normalizer/NormalizerContextBuilder.md

[23]: doc/Normalizer/NormalizerObjectMappingRegistry.md

[24]: doc/Mapping/NormalizationFieldMapping.md
[25]: doc/Mapping/NormalizationFieldMappingBuilder.md

[26]: doc/Mapping/NormalizationLinkMapping.md
[27]: doc/Mapping/NormalizationLinkMappingBuilder.md

[28]: doc/Mapping/AdvancedNormalizationObjectMapping.md
[29]: doc/Mapping/SimpleNormalizationObjectMapping.md

[30]: doc/Mapping/CallableNormalizationObjectMapping.md
[31]: doc/Mapping/LazyNormalizationObjectMapping.md

[32]: doc/ServiceFactory/SerializationServiceFactory.md

[33]: doc/ServiceProvider/SerializationServiceProvider.md
