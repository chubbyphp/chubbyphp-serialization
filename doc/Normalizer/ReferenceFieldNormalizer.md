# ReferenceFieldNormalizer

```php
<?php

use Chubbyphp\Serialization\Accessor\PropertyAccessor;
use Chubbyphp\Serialization\Normalizer\ReferenceFieldNormalizer;
use MyProject\Model\Model;
use MyProject\Model\ReferenceModel;

$model = new Model;
$model->setReference(new ReferenceModel);

$context = ...;
$normalizer = ...;

$fieldNormalizer = new ReferenceFieldNormalizer(
    new PropertyAccessor('children')
);

$data = $fieldNormalizer->normalizeField(
    'reference',
    $model,
    $context,
    $normalizer
);

print_r(
    $data
);
// ['name' => 'php']
```
