# NullPolicy

```php
<?php

use Chubbyphp\Serialization\Normalizer\NormalizerContextInterface;
use Chubbyphp\Serialization\Policy\NullPolicy;
use MyProject\Model\Model;

$model = new Model();

/** @var NormalizerContextInterface $context */
$context = ...;

$policy = new NullPolicy();

echo $policy->isCompliant('path', $model, $context);
// 1
```
