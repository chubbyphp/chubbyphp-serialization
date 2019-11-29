# SerializationServiceFactory

```php
<?php

use Chubbyphp\Serialization\ServiceFactory\SerializationServiceFactory;
use Chubbyphp\Container\Container;

$container = new Container();
$container->factories((new SerializationServiceFactory())());

$container->get('serializer')
    ->serialize(...);

$container->get('serializer.normalizer')
    ->normalize(...);

$container->get('serializer.encoder')
    ->encode(...);
```
