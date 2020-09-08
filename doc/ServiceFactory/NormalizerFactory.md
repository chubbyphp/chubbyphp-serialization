# NormalizerFactory

## without name (default)

```php
<?php

use Chubbyphp\Serialization\ServiceFactory\NormalizerFactory;
use Psr\Container\ContainerInterface;

/** @var ContainerInterface $container */
$container = ...;

$factory = new NormalizerFactory();

$normalizer = $factory($container);
```

## with name `default`

```php
<?php

use Chubbyphp\Serialization\ServiceFactory\NormalizerFactory;
use Psr\Container\ContainerInterface;

/** @var ContainerInterface $container */
$container = ...;

$factory = [NormalizerFactory::class, 'default'];

$normalizer = $factory($container);
```
