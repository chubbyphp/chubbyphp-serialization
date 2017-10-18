# NormalizerObjectMappingRegistry

```php
<?php

use Chubbyphp\Serialization\Normalizer\NormalizerObjectMappingRegistry;

$registry = new NormalizerObjectMappingRegistry([]);

echo $registry->getObjectMapping('class')->getClass();
// 'class'
```
