# NormalizerContext

```php
<?php

use Chubbyphp\Serialization\Normalizer\NormalizerContext;
use Psr\Http\Message\ServerRequestInterface;

$request = ...;

$context = new NormalizerContext($request, ['key' => 'value']);

$request = $context->getRequest();

print_r($context->getAttributes());
// ['key' => 'value']
```
