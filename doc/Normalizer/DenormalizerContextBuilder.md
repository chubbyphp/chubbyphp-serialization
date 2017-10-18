# NormalizerContextBuilder

```php
<?php

use Chubbyphp\Serialization\Normalizer\NormalizerContextBuilder;

$context = new NormalizerContextBuilder::create()
    ->setAllowedAdditionalFields(true)
    ->setGroups(['group1'])
    ->getContext();

echo $context->isAllowedAdditionalFields();
// true

print_r($context->getGroups());
// ['group1']
```
