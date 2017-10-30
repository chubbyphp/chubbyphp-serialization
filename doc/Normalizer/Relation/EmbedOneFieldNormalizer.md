# EmbedOneFieldNormalizer

```php
<?php

use Chubbyphp\Serialization\Accessor\PropertyAccessor;
use Chubbyphp\Serialization\Normalizer\Relation\EmbedOneFieldNormalizer;
use MyProject\Model\Model;
use MyProject\Model\RelationModel;

$model = new Model;
$model->setReference((new RelationModel)->setName('php'));

$context = ...;
$normalizer = ...;

$fieldNormalizer = new EmbedOneFieldNormalizer(
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
