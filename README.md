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

### Normalizer

 * [Normalizer][9]

#### Field Normalizer

 * [CallbackFieldNormalizer][10]
 * [CollectionFieldNormalizer][11]
 * [DateFieldNormalizer][12]
 * [FieldNormalizer][13]

#### Normalizer Context

 * [NormalizerContext][14]
 * [NormalizerContextBuilder][15]

### NormalizerObjectMappingRegistry

* [NormalizerObjectMappingRegistry][16]

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
    ]),
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

### Mapping

#### NormalizationFieldMapping

 * [NormalizationFieldMapping][17]
 * [NormalizationFieldMappingBuilder][18]

#### NormalizationObjectMapping

 * [AdvancedNormalizationObjectMapping][19]
 * [SimpleNormalizationObjectMapping][20]

#### LazyNormalizationObjectMapping

 * [LazyNormalizationObjectMapping][21]

### Provider

* [SerializationProvider][22]

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

[9]: doc/Normalizer/Normalizer.md

[10]: doc/Normalizer/CallbackFieldNormalizer.md
[11]: doc/Normalizer/CollectionFieldNormalizer.md
[12]: doc/Normalizer/DateFieldNormalizer.md
[13]: doc/Normalizer/FieldNormalizer.md

[14]: doc/Normalizer/NormalizerContext.md
[15]: doc/Normalizer/NormalizerContextBuilder.md

[16]: doc/Normalizer/NormalizerObjectMappingRegistry.md

[17]: doc/Mapping/NormalizationFieldMapping.md
[18]: doc/Mapping/NormalizationFieldMappingBuilder.md

[19]: doc/Mapping/AdvancedNormalizationObjectMapping.md
[20]: doc/Mapping/SimpleNormalizationObjectMapping.md

[21]: doc/Mapping/LazyNormalizationObjectMapping.md

[22]: doc/Provider/SerializationProvider.md
