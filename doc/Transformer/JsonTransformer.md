# JsonTransformer

```php
<?php

use Chubbyphp\Serialization\Transformer\JsonTransformer;

$transformer = new JsonTransformer();
$transformer->getContentType(); // 'application/json'
$transformer->transform($data); // transforms an array to json
```
