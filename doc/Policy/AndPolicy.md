# AndPolicy

```php
<?php

use Chubbyphp\Serialization\Normalizer\NormalizerContextInterface;
use Chubbyphp\Serialization\Policy\AndPolicy;
use MyProject\Model\Model;
use MyProject\Policy\AnotherPolicy;
use MyProject\Policy\SomePolicy;

$model = new Model();

/** @var NormalizerContextInterface $context */
$context = ...;

$policy = new AndPolicy([
    new SomePolicy(),
    new AnotherPolicy(),
]);

echo $policy->isCompliantIncludingPath('path', $model, $context);
// 1
```
