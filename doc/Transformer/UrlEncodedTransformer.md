# UrlEncodedTransformer

```php
<?php

use Chubbyphp\Serialization\Transformer\UrlEncodedTransformer;

$transformer = new UrlEncodedTransformer();
$transformer->getContentType(); // 'application/x-www-form-urlencoded'
$transformer->transform($data); // transforms an array to urlencoded string
```
