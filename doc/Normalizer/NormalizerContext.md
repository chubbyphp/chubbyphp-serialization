# NormalizerContext

```php
<?php

use Chubbyphp\Serialization\Normalizer\NormalizerContext;
use Psr\Http\Message\ServerRequestInterface as Request;

$request = ...;

$context = new NormalizerContext(['group1'], $request);

print_r($context->getGroups());
// ['group1']

$request = $context->getRequest();
```
