# SerializationProvider

```php
<?php

use Chubbyphp\Serialization\Provider\SerializationProvider;
use Pimple\Container;

$container = new Container();
$container->register(new SerializationProvider);

$container['serializer']
    ->serialize(...);

$container['serializer.normalizer']
    ->normalize(...);

$container['serializer.encoder']
    ->encode(...);
```
