# ValueFieldSerializer

```php
<?php

use Chubbyphp\Serialization\Accessor\MethodAccessor;
use Chubbyphp\Serialization\Serializer\Field\ValueFieldSerializer;
use MyProject\Model\Model;

$path = 'name';
$request = ...; // PSR7 Request
$object = new Model;

$fieldSerializer = new ValueFieldSerializer(new MethodAccessor('getName'));
$fieldSerializer->serializeField($path, $request, $object);
```
