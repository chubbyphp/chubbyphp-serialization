# NormalizationLinkMappingBuilder

```php
<?php

use Chubbyphp\Serialization\Link\Link;
use Chubbyphp\Serialization\Mapping\NormalizationLinkMappingBuilder;
use Chubbyphp\Serialization\Normalizer\CallbackLinkNormalizer;
use Chubbyphp\Serialization\Normalizer\NormalizerContextInterface;

$fieldMapping = NormalizationLinkMappingBuilder::create(
        'name',
        new CallbackLinkNormalizer(
            function (
                string $path,
                $object,
                NormalizerContextInterface $context
            ) {
                return new Link('/api/model');
            }
        )
    )
    ->setGroups(['group1'])
    ->getMapping();

echo $fieldMapping->getName();
// 'name'

print_r($fieldMapping->getGroups());
// ['group1']

$fieldMapping
    ->getLinkNormalizer()
    ->normalizeLink(...);
```
