# LazyObjectMapping

```php
<?php

use Chubbyphp\Serialization\LazyObjectMapping;
use MyProject\Model\Model;
use MyProject\Serialization\ModelMapping;

$container[ModelMapping::class] = function () use ($container) {
    return new ModelMapping();
};

$lazyObjectMapping = new LazyObjectMapping($container, ModelMapping::class, Model::class);
```
