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

echo $objectMapping->getNormalizationType();
// 'model'

$objectMapping->getNormalizationFieldMappings('');
// array<int, NormalizationFieldMappingInterface>

$objectMapping->getNormalizationEmbeddedFieldMappings('');
// array<int, NormalizationFieldMappingInterface>
```
