# DateFieldNormalizer

```php
<?php

use Chubbyphp\Serialization\Accessor\PropertyAccessor;
use Chubbyphp\Serialization\Normalizer\DateFieldNormalizer;
use Chubbyphp\Serialization\Normalizer\FieldNormalizer;
use MyProject\Model\Model;

$model = new Model;
$context = ...;

$fieldNormalizer = new DateFieldNormalizer(
    new FieldNormalizer(
        new PropertyAccessor('at')
    )
)

$fieldNormalizer->normalize(
    'at',
    $model,
    new \DateTime('2017-01-01 22:00:00'),
    $context
)

echo $model->getAt()
    ->format('Y-m-d H:i:s');
// '2017-01-01 22:00:00'
```
