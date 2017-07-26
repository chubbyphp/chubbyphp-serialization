# ObjectMappingRegistry

```php
<?php

use Chubbyphp\Serialization\Registry\ObjectMappingRegistry;
use MyProject\Model\Model;
use MyProject\Serialization\ModelMapping;

$objectMappingRegistry = new ObjectMappingRegistry([new ModelMapping]);
$objectMappingRegistry->getObjectMappingForClass(Model::class); // new ModelMapping()
```
