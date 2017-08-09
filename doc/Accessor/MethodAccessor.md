# MethodAccessor

```php
<?php

use Chubbyphp\Serialization\Accessor\MethodAccessor;

$accessor = new MethodAccessor('getterName');
$accessor->getValue($object); // the value returned by getterName on $object
```
