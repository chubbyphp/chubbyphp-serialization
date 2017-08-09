# CallbackLinkSerializer

```php
<?php

use Chubbyphp\Serialization\Serializer\Link\CallbackLinkSerializer;
use MyProject\Model\Model;

$path = 'field';
$request = ...; // PSR7 Request
$object = new Model;

$fieldSerializer = new CallbackLinkSerializer(
    function (Request $request, Model $object, array $fields) {
       return new Link('http//test.com', 'GET');
    }
);

$fieldSerializer->serializeLink($request, $object, $fields);
```
