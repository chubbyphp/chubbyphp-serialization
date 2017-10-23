# Link

```php
<?php

use Chubbyphp\Serialization\Link\Link;

$link = new Link(
    '/api/model',
    ['model'],
    ['method' => 'GET']
);

echo $link->getHref();
// '/api/model'

print_r($link->getRels());
// ['model']

print_r($link->getAttributes());
// ['method' => 'GET']
```
