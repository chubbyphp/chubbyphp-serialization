# MethodAccessor

```php
<?php

use Chubbyphp\Serialization\Accessor\MethodAccessor;
use MyProject\Model;

$object = new Model;
$object->setName('php');

$accessor = new MethodAccessor('name');

echo $accessor->getValue($object);
// 'php'
```
