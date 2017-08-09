# Link

```php
<?php

use Chubbyphp\Serialization\Link\Link;

$link = new Link('http://test.com', 'GET', ['key' => 'value']);
$link->jsonSerialize(); // ['href' => 'http://test.com', 'method' => 'GET', 'attributes' => ['key' => 'value']]
```
