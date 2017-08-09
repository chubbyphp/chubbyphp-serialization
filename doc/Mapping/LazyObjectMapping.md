# LazyObjectMapping

```php
<?php

use Chubbyphp\Serialization\Mapping\LazyObjectMapping;
use MyProject\Model\Model;
use MyProject\Serialization\ModelMapping;

$container[ModelMapping::class] = function () use ($container) {
    return new ModelMapping();
};

$lazyObjectMapping = new LazyObjectMapping($container, ModelMapping::class, Model::class);

$lazyObjectMapping->getClass(); // 'MyProject\Model\Model'
$lazyObjectMapping->getType(); // 'type'
$lazyObjectMapping->getFieldMappings(); // array of FieldMapping
$lazyObjectMapping->getEmbeddedFieldMappings(); // array of FieldMapping
$lazyObjectMapping->getLinkMappings(); // array of LinkMapping
```
