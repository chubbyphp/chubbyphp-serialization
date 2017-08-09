# CollectionFieldSerializer

```php
<?php

use Chubbyphp\Serialization\Accessor\MethodAccessor;
use Chubbyphp\Serialization\Serializer\Field\CollectionFieldSerializer;
use Chubbyphp\Serialization\Serializer;
use MyProject\Model\Model;

$path = 'posts';
$request = ...; // PSR7 Request
$object = new Model;
$serializer = new Serializer(...);

$fieldSerializer = new CollectionFieldSerializer(new MethodAccessor('getPosts'));
$fieldSerializer->serializeField($path, $request, $object, $serializer);
```
