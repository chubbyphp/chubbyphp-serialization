# SerializationProvider

```php
<?php

use Chubbyphp\Serialization\Provider\SerializationProvider;
use MyProject\Serialization\ModelMapping;
use Pimple\Container;

$container = new Container();
$container->register(new SerializationProvider());

$container->extend('serializer.objectmappings', function (array $objectMappings) use ($container) {
    $objectMappings[] = new ModelMapping(...);

    return $objectMappings;
});

$data = $container['serializer']->serialize($model); // array of data

$json = $container['serializer.transformer.json']->transform($data); // json string
$urlEncoded = $container['serializer.transformer.urlencoded']->transform($data); // urlencoded string
$xml = $container['serializer.transformer.xml']->transform($data); // xml string
$yaml = $container['serializer.transformer.yaml']->transform($data); // yaml string
```
