# CallbackFieldNormalizer

```php
<?php

use Chubbyphp\Serialization\Normalizer\CallbackFieldNormalizer;
use Chubbyphp\Serialization\Normalizer\NormalizerContextInterface;
use Chubbyphp\Serialization\Normalizer\NormalizerInterface;
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
);

echo $fieldNormalizer->normalizeField(
    'name',
    $model,
    $context
);
// 'php'
```
