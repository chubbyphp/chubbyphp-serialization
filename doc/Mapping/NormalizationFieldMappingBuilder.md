# NormalizationFieldMappingBuilder

```php
<?php

use Chubbyphp\Serialization\Accessor\PropertyAccessor;
use Chubbyphp\Serialization\Normalizer\FieldNormalizer;
use Chubbyphp\Serialization\Mapping\NormalizationFieldMappingBuilder;

$fieldMapping = NormalizationFieldMappingBuilder::create('name')
    ->setFieldNormalizer(
        new FieldNormalizer(
            new PropertyAccessor('name')
        )
    )
    ->getMapping();

echo $fieldMapping->getName();
// 'name'

print_r($fieldMapping->getGroups());
// ['group1']

$fieldMapping
    ->getFieldNormalizer()
    ->normalizeField(...);
```
