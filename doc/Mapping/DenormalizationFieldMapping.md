# NormalizationFieldMapping

```php
<?php

use Chubbyphp\Serialization\Accessor\PropertyAccessor;
use Chubbyphp\Serialization\Normalizer\FieldNormalizer;
use Chubbyphp\Serialization\Mapping\NormalizationFieldMapping;

$fieldMapping = new NormalizationFieldMapping(
    'name',
    ['group1'],
    new FieldNormalizer(
        new PropertyAccessor('name')
    )
);

echo $fieldMapping->getName();
// 'name'

print_r($fieldMapping->getGroups());
// ['group1']

$fieldMapping
    ->getFieldNormalizer()
    ->normalizeField(...);
```
