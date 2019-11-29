# SerializationServiceProvider

```php
<?php

use Chubbyphp\Serialization\ServiceProvider\SerializationServiceProvider;
use Pimple\Container;

$container = new Container();
$container->register(new SerializationServiceProvider);

$container['serializer']
    ->serialize(...);

$container['serializer.normalizer']
    ->normalize(...);

$container['serializer.encoder']
    ->encode(...);
```
