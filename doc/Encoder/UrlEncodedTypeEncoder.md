# UrlEncodedTypeEncoder

```php
<?php

use Chubbyphp\Serialization\Encoder\UrlEncodedTypeEncoder;

$encoderType = new UrlEncodedTypeEncoder();

echo $encoderType->getContentType();
// 'application/x-www-form-urlencoded'

echo $encoderType->encode(['name' => 'php']);
// 'name=php'
```
