# YamlTypeEncoder

```php
<?php

use Chubbyphp\Serialization\Encoder\YamlTypeEncoder;

$encoderType = new YamlTypeEncoder();

echo $encoderType->getContentType();
// 'application/x-yaml'

echo $encoderType->encode(['name' => 'php']);
// 'name: php'
```
