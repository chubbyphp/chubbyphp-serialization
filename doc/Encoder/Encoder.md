# Encoder

```php
<?php

use Chubbyphp\Deserialization\Encoder\Encoder;
use Chubbyphp\Deserialization\Encoder\JsonTypeEncoder;
use Chubbyphp\Deserialization\Encoder\UrlEncodedTypeEncoder;
use Chubbyphp\Deserialization\Encoder\XmlTypeEncoder;
use Chubbyphp\Deserialization\Encoder\YamlTypeEncoder;

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
// '{"name": "php"}'
```