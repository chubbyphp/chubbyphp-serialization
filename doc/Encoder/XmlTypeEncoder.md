# XmlTypeEncoder  (alias for Jsonx)

```php
<?php

use Chubbyphp\Serialization\Encoder\XmlTypeEncoder;

$encoderType = new XmlTypeEncoder();

echo $encoderType->getContentType();
// 'application/xml'

echo $encoderType->encode(['name' => 'php']);
// '<json:object><json:string name="name">php</json:string></json:object>'
```
