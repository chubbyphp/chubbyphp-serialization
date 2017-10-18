# FieldNormalizer

```php
<?php

use Chubbyphp\Serialization\Accessor\PropertyAccessor;
use Chubbyphp\Serialization\Normalizer\FieldNormalizer;
use MyProject\Model\Model;

$model = new Model;
$context = ...;

$fieldNormalizer = new FieldNormalizer(
    new PropertyAccessor('name')
)

$fieldNormalizer->normalize(
    'name',
    $model,
    'php',
    $context
)

echo $model->getName();
// 'php'
```
