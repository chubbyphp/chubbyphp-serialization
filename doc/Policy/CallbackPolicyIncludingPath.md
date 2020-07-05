# CallbackPolicyIncludingPath

```php
<?php

use Chubbyphp\Serialization\Normalizer\NormalizerContextInterface;
use Chubbyphp\Serialization\Policy\CallbackPolicyIncludingPath;
use MyProject\Model\Model;

$model = new Model();

/** @var NormalizerContextInterface $context */
$context = ...;

$policy = new CallbackPolicyIncludingPath(function (string $path, object $object, NormalizerContextInterface $context) {
    return true;
});

echo $policy->isCompliantIncludingPath('path', $model, $context);
// 1
```
