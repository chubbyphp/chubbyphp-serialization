# CollectionFieldNormalizer

```php
<?php

use Chubbyphp\Serialization\Accessor\PropertyAccessor;
use Chubbyphp\Serialization\Normalizer\CollectionFieldNormalizer;
use MyProject\Model\ParentModel;
use MyProject\Model\ChildModel;

$parentModel = new ParentModel;
$context = ...;
$normalizer = ...;

$fieldNormalizer = new CollectionFieldNormalizer(
    ChildModel::class
    new PropertyAccessor('children')
)

$fieldNormalizer->normalize(
    'children',
    $parentModel,
    [['name' => 'php'],
    $context,
    $normalizer
)

echo $parentModel
    ->getChildren()[0]
    ->getName();
// 'php'
```
