# YamlTransformer

```php
<?php

use Chubbyphp\Serialization\Transformer\YamlTransformer;

$transformer = new YamlTransformer();
$transformer->getContentType(); // 'application/x-yaml'
$transformer->transform($data); // transforms an array to yaml
```
