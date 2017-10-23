# NormalizerContextBuilder

```php
<?php

use Chubbyphp\Serialization\Normalizer\NormalizerContextBuilder;
use Psr\Http\Message\ServerRequestInterface as Request;

$request = ...;

$context = NormalizerContextBuilder::create()
    ->setGroups(['group1'])
    ->setRequest($request)
    ->getContext();

print_r($context->getGroups());
// ['group1']

$request = $context->getRequest();
```
