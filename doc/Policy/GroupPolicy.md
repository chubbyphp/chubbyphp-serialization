# GroupPolicy

```php
<?php

use Chubbyphp\Serialization\Normalizer\NormalizerContextInterface;
use Chubbyphp\Serialization\Policy\GroupPolicy;
use MyProject\Model\Model;

$model = new Model();

/** @var NormalizerContextInterface $context */
$context = ...;
$context = $context->withAttribute('groups', ['group1']);

$policy = new GroupPolicy(['group1']);

echo $policy->isCompliantIncludingPath('path', $model, $context);
// 1
```
