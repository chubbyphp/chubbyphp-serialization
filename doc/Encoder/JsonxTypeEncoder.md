# JsonxTypeEncoder

```php
<?php

use Chubbyphp\Serialization\Encoder\JsonxTypeEncoder;

$encoderType = new JsonxTypeEncoder();

echo $encoderType->getContentType();
// 'application/jsonx+xml'

echo $encoderType->encode(['name' => 'php']);
// '<json:object><json:string name="name">php</json:string></json:object>'
```
