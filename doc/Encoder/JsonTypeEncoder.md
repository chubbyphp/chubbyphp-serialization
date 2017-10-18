# JsonTypeEncoder

```php
<?php

use Chubbyphp\Serialization\Encoder\JsonTypeEncoder;

$encoderType = new JsonTypeEncoder();

echo $encoderType->getContentType();
// 'application/json'

echo $encoderType->encode(['name' => 'php']);
// '{"name": "php"}'
```
