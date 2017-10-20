# CollectionFieldNormalizer

```php
<?php

use Chubbyphp\Serialization\Accessor\PropertyAccessor;
use Chubbyphp\Serialization\Normalizer\CollectionFieldNormalizer;
use MyProject\Model\ParentModel;
use MyProject\Model\ChildModel;
use Psr\Http\Message\ServerRequestInterface as Request;

$parentModel = new ParentModel;
$request = ...;
$context = ...;
$normalizer = ...;

$fieldNormalizer = new CollectionFieldNormalizer(
    new PropertyAccessor('children')
)

$data = $fieldNormalizer->normalize(
    'children',
    $request,
    $parentModel,
    $context,
    $normalizer
)

print_r(
    $data
);
// [['name' => 'php']]
```
