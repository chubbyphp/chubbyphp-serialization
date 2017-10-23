# NormalizationLinkMapping

```php
<?php

use Chubbyphp\Serialization\Link\Link;
use Chubbyphp\Serialization\Mapping\NormalizationLinkMapping;
use Chubbyphp\Serialization\Normalizer\CallbackLinkNormalizer;
use Chubbyphp\Serialization\Normalizer\NormalizerContextInterface;

$fieldMapping = new NormalizationLinkMapping(
    'name',
    ['group1'],
    new CallbackLinkNormalizer(
        function (
            string $path,
            $object,
            NormalizerContextInterface $context
        ) {
            return new Link('/api/model');
        }
    )
);

echo $fieldMapping->getName();
// 'name'

print_r($fieldMapping->getGroups());
// ['group1']

$fieldMapping
    ->getLinkNormalizer()
    ->normalizeLink(...);
```
