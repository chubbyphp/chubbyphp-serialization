# CollectionFieldNormalizer

```php
<?php

use Chubbyphp\Serialization\Accessor\PropertyAccessor;
use Chubbyphp\Serialization\Normalizer\CollectionFieldNormalizer;
use MyProject\Model\ParentModel;
use MyProject\Model\ChildModel;

$parentModel = new ParentModel;
$parentModel->setChildren([new ChildModel]);
$context = ...;
$normalizer = ...;

$fieldNormalizer = new CollectionFieldNormalizer(
    new PropertyAccessor('children')
);

$data = $fieldNormalizer->normalizeField(
    'children',
    $parentModel,
    $context,
    $normalizer
);

print_r(
    $data
);
// [['name' => 'php']]
```
