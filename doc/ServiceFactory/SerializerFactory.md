# SerializerFactory

## without name (default)

```php
<?php

use Chubbyphp\Serialization\ServiceFactory\SerializerFactory;
use Psr\Container\ContainerInterface;

/** @var ContainerInterface $container */
$container = ...;

$factory = new SerializerFactory();

$serializer = $factory($container);
```

## with name `default`

```php
<?php

use Chubbyphp\Serialization\ServiceFactory\SerializerFactory;
use Psr\Container\ContainerInterface;

/** @var ContainerInterface $container */
$container = ...;

$factory = [SerializerFactory::class, 'default'];

$serializer = $factory($container);
```
