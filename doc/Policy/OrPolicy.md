# OrPolicy

```php
<?php

use Chubbyphp\Serialization\Normalizer\NormalizerContextInterface;
use Chubbyphp\Serialization\Policy\OrPolicy;
use MyProject\Model\Model;
use MyProject\Policy\AnotherPolicy;
use MyProject\Policy\SomePolicy;

$model = new Model();

/** @var NormalizerContextInterface $context */
$context = ...;

$policy = new OrPolicy([
    new SomePolicy(),
    new AnotherPolicy(),
]);

echo $policy->isCompliant('path', $model, $context);
// 1
```
