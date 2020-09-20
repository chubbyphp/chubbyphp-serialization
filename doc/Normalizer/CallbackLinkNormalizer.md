# CallbackLinkNormalizer

```php
<?php

use Chubbyphp\Serialization\Link\Link;
use Chubbyphp\Serialization\Normalizer\CallbackLinkNormalizer;
use Chubbyphp\Serialization\Normalizer\NormalizerContextInterface;
use MyProject\Model\Model;

$model = new Model;
$context = ...;

$fieldNormalizer = new CallbackLinkNormalizer(
    function (
        string $path,
        object $object,
        NormalizerContextInterface $context
    ) {
        return new Link('/api/model');
    }
);

echo $fieldNormalizer->normalizeLink(
    '',
    $model,
    $context
);
// 'php'
```
