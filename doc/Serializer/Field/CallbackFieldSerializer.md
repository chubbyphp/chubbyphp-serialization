# CallbackFieldSerializer

```php
<?php

use Chubbyphp\Serialization\Serializer\Field\CallbackFieldSerializer;
use MyProject\Model\Model;

$path = 'name';
$request = ...; // PSR7 Request
$object = new Model;

$fieldSerializer = new CallbackFieldSerializer(
    function (
        string $path,
        Request $request,
        $object,
        SerializerInterface $serializer = null
    ) {
       return $object->getName();
    }
);

$fieldSerializer->serializeField($path, $request, $object);
```
