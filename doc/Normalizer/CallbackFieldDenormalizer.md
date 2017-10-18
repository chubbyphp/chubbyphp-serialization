# CallbackFieldNormalizer

```php
<?php

use Chubbyphp\Serialization\Normalizer\CallbackFieldNormalizer;
use MyProject\Model\Model;

$model = new Model;
$context = ...;

$fieldNormalizer = new CallbackFieldNormalizer(
    function (
        string $path,
        $object,
        $value,
        NormalizerContextInterface $context,
        NormalizerInterface $normalizer = null
    ) {
        $object->setName($value);
    }
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
