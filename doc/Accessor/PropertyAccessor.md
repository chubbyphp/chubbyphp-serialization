# PropertyAccessor

```php
<?php

use Chubbyphp\Serialization\Accessor\PropertyAccessor;

$accessor = new PropertyAccessor('propertyName');
$accessor->getValue($object); // the value returned by propertyName on $object
```
