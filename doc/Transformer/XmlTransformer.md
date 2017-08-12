# XmlTransformer

```php
<?php

use Chubbyphp\Serialization\Transformer\XmlTransformer;

$transformer = new XmlTransformer();
$transformer->getContentType(); // 'application/xml'
$transformer->transform($data); // transforms an array to xml
```
