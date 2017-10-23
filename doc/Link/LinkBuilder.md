# LinkBuilder

```php
<?php

use Chubbyphp\Serialization\Link\LinkBuilder;

$link = LinkBuilder::create('/api/model')
    ->setRels(['model'])
    ->setAttributes(['method' => 'GET'])
    ->getLink();

echo $link->getHref();
// '/api/model'

print_r($link->getRels());
// ['model']

print_r($link->getAttributes());
// ['method' => 'GET']
```
