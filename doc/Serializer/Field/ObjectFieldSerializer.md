# ObjectFieldSerializer

```php
<?php

use Chubbyphp\Serialization\Accessor\MethodAccessor;
use Chubbyphp\Serialization\Serializer\Field\ObjectFieldSerializer;
use Chubbyphp\Serialization\Serializer;
use MyProject\Model\Model;

$path = 'user';
$request = ...; // PSR7 Request
$object = new Model;
$serializer = new Serializer(...);

$fieldSerializer = new ObjectFieldSerializer(new MethodAccessor('getUser'));
$fieldSerializer->serializeField($path, $request, $object, $serializer);
```
