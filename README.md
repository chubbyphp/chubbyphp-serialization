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
 * doctrine/inflector: ~1.0
 * psr/http-message: ~1.0
 * psr/link: ~1.0
 * psr/log: ~1.0

## Suggest

 * container-interop/container-interop: ~1.0
 * pimple/pimple: ~3.0
 * symfony/yaml: ~2.7|~3.0 (application/x-yaml support)

## Installation

Through [Composer](http://getcomposer.org) as [chubbyphp/chubbyphp-serialization][1].

```sh
composer require chubbyphp/chubbyphp-serialization "~2.0@alpha"
```

## Usage

### Accessor

 * [MethodAccessor][2]
 * [PropertyAccessor][3]

### Encoder

 * [Encoder][4]

#### Type Encoder

 * [JsonTypeEncoder][5]
 * [UrlEncodedTypeEncoder][6]
 * [XmlTypeEncoder][7]
 * [YamlTypeEncoder][8]

### Link

 * [Link][9]
 * [LinkBuilder][10]

### Normalizer

 * [Normalizer][11]

#### Field Normalizer

 * [CallbackFieldNormalizer][12]
 * [CollectionFieldNormalizer][13]
 * [DateFieldNormalizer][14]
 * [FieldNormalizer][15]

#### Normalizer Context

 * [NormalizerContext][16]
 * [NormalizerContextBuilder][17]

### NormalizerObjectMappingRegistry

* [NormalizerObjectMappingRegistry][18]

### Mapping

#### NormalizationFieldMapping

 * [NormalizationFieldMapping][19]
 * [NormalizationFieldMappingBuilder][20]
 
#### NormalizationLinkMapping

 * [NormalizationLinkMapping][21]
 * [NormalizationLinkMappingBuilder][22]

#### NormalizationObjectMapping

 * [NormalizationObjectMapping][23]

#### LazyNormalizationObjectMapping

 * [LazyNormalizationObjectMapping][24]

### Provider

* [SerializationProvider][25]

### Serializer

```php
<?php

use Chubbyphp\Serialization\Encoder\Encoder;
use Chubbyphp\Serialization\Encoder\JsonTypeEncoder;
use Chubbyphp\Serialization\Encoder\UrlEncodedTypeEncoder;
use Chubbyphp\Serialization\Encoder\XmlTypeEncoder;
use Chubbyphp\Serialization\Encoder\YamlTypeEncoder;
use Chubbyphp\Serialization\Normalizer\Normalizer;
use Chubbyphp\Serialization\Normalizer\NormalizerObjectMappingRegistry;
use Chubbyphp\Serialization\Serializer;
use MyProject\Serialization\ModelMapping;
use MyProject\Model\Model;
use Psr\Http\Message\ServerRequestInterface as Request;

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

Dominik Zogg 2017


[1]: https://packagist.org/packages/chubbyphp/chubbyphp-serialization

[2]: doc/Accessor/MethodAccessor.md
[3]: doc/Accessor/PropertyAccessor.md

[4]: doc/Encoder/Encoder.md

[5]: doc/Encoder/JsonTypeEncoder.md
[6]: doc/Encoder/UrlEncodedTypeEncoder.md
[7]: doc/Encoder/XmlTypeEncoder.md
[8]: doc/Encoder/YamlTypeEncoder.md

[9]: doc/Link/Link.md
[10]: doc/Link/LinkBuilder.md

[11]: doc/Normalizer/Normalizer.md

[12]: doc/Normalizer/CallbackFieldNormalizer.md
[13]: doc/Normalizer/CollectionFieldNormalizer.md
[14]: doc/Normalizer/DateFieldNormalizer.md
[15]: doc/Normalizer/FieldNormalizer.md

[16]: doc/Normalizer/NormalizerContext.md
[17]: doc/Normalizer/NormalizerContextBuilder.md

[18]: doc/Normalizer/NormalizerObjectMappingRegistry.md

[19]: doc/Mapping/NormalizationFieldMapping.md
[20]: doc/Mapping/NormalizationFieldMappingBuilder.md

[21]: doc/Mapping/NormalizationLinkMapping.md
[22]: doc/Mapping/NormalizationLinkMappingBuilder.md

[23]: doc/Mapping/NormalizationObjectMapping.md

[24]: doc/Mapping/LazyNormalizationObjectMapping.md

[25]: doc/Provider/SerializationProvider.md
