# LazyNormalizationObjectMapping

```php
<?php

use Chubbyphp\Serialization\Mapping\LazyNormalizationObjectMapping;
use MyProject\Model\Model;

$container = ...;

$objectMapping = new LazyNormalizationObjectMapping(
    $container,
    'myproject.normalizer.mapping.model',
    Model::class
);

echo $objectMapping->getClass();
// 'MyProject\Model\Model'

$callable = $objectMapping->getFactory('');
$model = $callable();

echo get_class($model);
// 'MyProject\Model\Model'

$objectMapping->getNormalizationFieldMappings('');
// NormalizationFieldMappingInterface[]
```
