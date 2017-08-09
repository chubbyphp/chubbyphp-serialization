# LinkMapping

```php
<?php

use Chubbyphp\Serialization\Mapping\LinkMapping;

$linkSerializer = ...;

$linkMapping = new LinkMapping('name', $linkSerializer);
$linkMapping->getName(); // name
$linkMapping->getLinkSerializer(); // $linkSerializer
```
