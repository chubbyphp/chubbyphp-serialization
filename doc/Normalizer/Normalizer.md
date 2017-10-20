## Normalizer

```php
<?php

use Chubbyphp\Serialization\Normalizer\Normalizer;
use Chubbyphp\Serialization\Normalizer\NormalizerObjectMappingRegistry;
use MyProject\Serialization\ModelMapping;
use MyProject\Model\Model;
use Psr\Http\Message\ServerRequestInterface as Request;

$logger = ...;

$normalizer = new Normalizer(
    new NormalizerObjectMappingRegistry([
        new ModelMapping()
    ]),
    $logger
);

$model = new Model;
$model->setName('php');

$request = ...;

$data = $normalizer->normalize(
    $request,
    $model
);

print_r($data);
// ['name' => 'php']
```
