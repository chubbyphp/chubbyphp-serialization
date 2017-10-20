# FieldNormalizer

```php
<?php

use Chubbyphp\Serialization\Accessor\PropertyAccessor;
use Chubbyphp\Serialization\Normalizer\FieldNormalizer;
use MyProject\Model\Model;
use Psr\Http\Message\ServerRequestInterface as Request;

$model = new Model;
$request = ...;
$context = ...;

$fieldNormalizer = new FieldNormalizer(
    new PropertyAccessor('name')
)

echo $fieldNormalizer->normalize(
    'name',
    $request,
    $model,
    $context
)
// 'php'
```
