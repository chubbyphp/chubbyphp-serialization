# EncoderFactory

## without name (default)

```php
<?php

use Chubbyphp\Serialization\Encoder\TypeEncoderInterface;
use Chubbyphp\Serialization\ServiceFactory\EncoderFactory;
use Psr\Container\ContainerInterface;

/** @var ContainerInterface $container */
$container = ...;

// $container->get(TypeEncoderInterface::class.'[]')

$factory = new EncoderFactory();

$encoder = $factory($container);
```

## with name `default`

```php
<?php

use Chubbyphp\Serialization\Encoder\TypeEncoderInterface;
use Chubbyphp\Serialization\ServiceFactory\EncoderFactory;
use Psr\Container\ContainerInterface;

/** @var ContainerInterface $container */
$container = ...;

// $container->get(TypeEncoderInterface::class.'[]default')

$factory = [EncoderFactory::class, 'default'];

$encoder = $factory($container);
```
