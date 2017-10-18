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

```php
<?php

use Chubbyphp\Serialization\Encoder\Encoder;
use Chubbyphp\Serialization\Encoder\JsonTypeEncoder;
use Chubbyphp\Serialization\Encoder\UrlEncodedTypeEncoder;
use Chubbyphp\Serialization\Encoder\XmlTypeEncoder;
use Chubbyphp\Serialization\Encoder\YamlTypeEncoder;

$encoder = new Encoder([
    new JsonTypeEncoder(),
    new UrlEncodedTypeEncoder(),
    new XmlTypeEncoder(),
    new YamlTypeEncoder()
]);

print_r($encoder->getContentTypes());
//[
//    'application/json',
//    'application/x-www-form-urlencoded',
//    'application/xml',
//    'application/x-yaml'
//]

echo $encoder->encode(
    ['name' => 'php'],
    'application/json'
);
// '{"name": "php"}',
```

#### Type Encoder

 * [JsonTypeEncoder][4]
 * [UrlEncodedTypeEncoder][5]
 * [XmlTypeEncoder][6]
 * [YamlTypeEncoder][7]

### Normalizer

```php
<?php

use Chubbyphp\Serialization\Normalizer\Normalizer;
use Chubbyphp\Serialization\Normalizer\NormalizerObjectMappingRegistry;
use MyProject\Serialization\ModelMapping;
use MyProject\Model\Model;

$logger = ...;

$normalizer = new Normalizer(
    new NormalizerObjectMappingRegistry([
        new ModelMapping()
    ]),
    $logger
);

$model = new Model;
$model->setName('php');

$data = $normalizer->normalize(
    $model
);

print_r($data);
// ['name' => 'php']
```

#### Field Normalizer

 * [CallbackFieldNormalizer][8]
 * [CollectionFieldNormalizer][9]
 * [DateFieldNormalizer][10]
 * [FieldNormalizer][11]

#### Normalizer Context

 * [NormalizerContext][12]
 * [NormalizerContextBuilder][13]

### NormalizerObjectMappingRegistry

* [NormalizerObjectMappingRegistry][14]

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
```

### Mapping

#### NormalizationFieldMapping

 * [NormalizationFieldMapping][15]
 * [NormalizationFieldMappingBuilder][16]

#### NormalizationObjectMapping

 * [AdvancedNormalizationObjectMapping][17]
 * [SimpleNormalizationObjectMapping][18]

#### LazyNormalizationObjectMapping

 * [LazyNormalizationObjectMapping][19]

### Provider

* [SerializationProvider][20]

## Copyright

Dominik Zogg 2017


[1]: https://packagist.org/packages/chubbyphp/chubbyphp-serialization

[2]: doc/Accessor/MethodAccessor.md
[3]: doc/Accessor/PropertyAccessor.md

[4]: doc/Encoder/JsonTypeEncoder.md
[5]: doc/Encoder/UrlEncodedTypeEncoder.md
[6]: doc/Encoder/XmlTypeEncoder.md
[7]: doc/Encoder/YamlTypeEncoder.md

[8]: doc/Normalizer/CallbackFieldNormalizer.md
[9]: doc/Normalizer/CollectionFieldNormalizer.md
[10]: doc/Normalizer/DateFieldNormalizer.md
[11]: doc/Normalizer/FieldNormalizer.md

[12]: doc/Normalizer/NormalizerContext.md
[13]: doc/Normalizer/NormalizerContextBuilder.md

[14]: doc/Normalizer/NormalizerObjectMappingRegistry.md

[15]: doc/Mapping/NormalizationFieldMapping.md
[16]: doc/Mapping/NormalizationFieldMappingBuilder.md

[17]: doc/Mapping/AdvancedNormalizationObjectMapping.md
[18]: doc/Mapping/SimpleNormalizationObjectMapping.md

[19]: doc/Mapping/LazyNormalizationObjectMapping.md

[20]: doc/Provider/SerializationProvider.md
