# NormalizerContextBuilder

```php
<?php

use Chubbyphp\Serialization\Normalizer\NormalizerContextBuilder;

$context = new NormalizerContextBuilder::create()
    ->setGroups(['group1'])
    ->getContext();

print_r($context->getGroups());
// ['group1']
```
