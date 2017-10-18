# NormalizerContext

```php
<?php

use Chubbyphp\Serialization\Normalizer\NormalizerContext;

$context = new NormalizerContext(true, ['group1']);

echo $context->isAllowedAdditionalFields();
// true

print_r($context->getGroups());
// ['group1']
```
