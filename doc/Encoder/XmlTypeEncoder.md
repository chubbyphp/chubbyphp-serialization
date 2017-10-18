# XmlTypeEncoder

```php
<?php

use Chubbyphp\Serialization\Encoder\XmlTypeEncoder;

$encoderType = new XmlTypeEncoder();

echo $encoderType->getContentType();
// 'application/xml'

echo $encoderType->encode(['name' => 'php']);
// '<name type="string">php</name>'
```
