# FieldMapping

```php
<?php

use Chubbyphp\Serialization\Mapping\FieldMapping;

$fieldSerializer = ...;

$fieldMapping = new FieldMapping('name', $fieldSerializer);
$fieldMapping->getName(); // name
$fieldMapping->getFieldSerializer(); // $fieldSerializer
```
