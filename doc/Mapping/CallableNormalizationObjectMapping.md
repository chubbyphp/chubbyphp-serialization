# CallableNormalizationObjectMapping

```php
<?php

use Chubbyphp\Serialization\Mapping\CallableNormalizationObjectMapping;
use MyProject\Mapping\ModelMapping;
use MyProject\Model\Model;

$objectMapping = new CallableNormalizationObjectMapping(
    Model::class,
    function () {
        return new ModelMapping();
    }
);

echo $objectMapping->getClass();
// 'MyProject\Model\Model'

echo $objectMapping->getNormalizationType();
// 'model'

$objectMapping->getNormalizationFieldMappings('');
// NormalizationFieldMappingInterface[]

$objectMapping->getNormalizationEmbeddedFieldMappings('');
// NormalizationFieldMappingInterface[]
```
