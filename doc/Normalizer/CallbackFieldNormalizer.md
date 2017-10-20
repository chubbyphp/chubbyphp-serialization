# CallbackFieldNormalizer

```php
<?php

use Chubbyphp\Serialization\Normalizer\CallbackFieldNormalizer;
use MyProject\Model\Model;
use Psr\Http\Message\ServerRequestInterface as Request;

$model = new Model;
$request = ...;
$context = ...;

$fieldNormalizer = new CallbackFieldNormalizer(
    function (
        string $path,
        Request $request,
        $object,
        NormalizerContextInterface $context,
        NormalizerInterface $normalizer = null
    ) {
        return $object->getName();
    }
)

echo $fieldNormalizer->normalize(
    'name',
    $request,
    $model,
    'php',
    $context
)
// 'php'
```
