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
        NormalizerContextInterface $context,
        NormalizerInterface $normalizer = null
    ) {
        return $object->getName();
    }
)

echo $fieldNormalizer->normalize(
    'name',
    $model,
    'php',
    $context
)
// 'php'
```
