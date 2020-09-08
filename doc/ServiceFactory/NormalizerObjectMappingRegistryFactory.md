# NormalizerObjectMappingRegistryFactory

## without name (default)

```php
<?php

use Chubbyphp\Serialization\Mapping\NormalizationObjectMappingInterface;
use Chubbyphp\Serialization\ServiceFactory\NormalizerObjectMappingRegistryFactory;
use Psr\Container\ContainerInterface;

/** @var ContainerInterface $container */
$container = ...;

// $container->get(NormalizationObjectMappingInterface::class.'[]')

$factory = new NormalizerObjectMappingRegistryFactory();

$normalizerObjectMappingRegistry = $factory($container);
```

## with name `default`

```php
<?php

use Chubbyphp\Serialization\Mapping\NormalizationObjectMappingInterface;
use Chubbyphp\Serialization\ServiceFactory\NormalizerObjectMappingRegistryFactory;
use Psr\Container\ContainerInterface;

/** @var ContainerInterface $container */
$container = ...;

// $container->get(NormalizationObjectMappingInterface::class.'[]default')

$factory = [NormalizerObjectMappingRegistryFactory::class, 'default'];

$normalizerObjectMappingRegistry = $factory($container);
```
