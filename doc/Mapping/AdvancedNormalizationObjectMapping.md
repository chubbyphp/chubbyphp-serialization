# AdvancedNormalizationObjectMapping

## Mapping

### ModelMapping

```php
<?php

namespace MyProject\Serialization;

use Chubbyphp\Serialization\Accessor\PropertyAccessor;
use Chubbyphp\Serialization\Link\LinkBuilder;
use Chubbyphp\Serialization\Mapping\NormalizationFieldMappingBuilder;
use Chubbyphp\Serialization\Mapping\NormalizationFieldMappingInterface;
use Chubbyphp\Serialization\Mapping\NormalizationLinkMapping;
use Chubbyphp\Serialization\Mapping\NormalizationLinkMappingInterface;
use Chubbyphp\Serialization\Mapping\NormalizationObjectMappingInterface;
use Chubbyphp\Serialization\Normalizer\CallbackLinkNormalizer;
use Chubbyphp\Serialization\Normalizer\Relation\EmbedManyFieldNormalizer;
use Chubbyphp\Serialization\Normalizer\Relation\EmbedOneFieldNormalizer;
use Chubbyphp\Serialization\Policy\GroupPolicy;
use MyProject\Model\Model;

final class ModelMapping implements NormalizationObjectMappingInterface
{
    /**
     * @return string
     */
    public function getClass(): string
    {
        return Model::class;
    }

    /**
     * @return string
     */
    public function getNormalizationType(): string
    {
        return 'model';
    }

    /**
     * @param string      $path
     * @param string|null $type
     *
     * @return array<int, NormalizationFieldMappingInterface>
     */
    public function getNormalizationFieldMappings(string $path, string $type = null): array
    {
        return [
            NormalizationFieldMappingBuilder::create('id')
                ->setPolicy(new GroupPolicy(['baseInformation']))
                ->getMapping(),
            NormalizationFieldMappingBuilder::create('name')
                ->setPolicy(new GroupPolicy(['baseInformation']))
                ->getMapping(),
            NormalizationFieldMappingBuilder::createEmbedOne('one')->getMapping(),
            NormalizationFieldMappingBuilder::createEmbedMany('manies')->getMapping(),
        ];
    }

    /**
     * @param string $path
     *
     * @return array<int, NormalizationFieldMappingInterface>
     */
    public function getNormalizationEmbeddedFieldMappings(string $path): array
    {
        return [];
    }

    /**
     * @param string $path
     *
     * @return array<int, NormalizationLinkMappingInterface>
     */
    public function getNormalizationLinkMappings(string $path): array
    {
        return [
            new NormalizationLinkMapping(
                'self',
                [],
                new CallbackLinkNormalizer(
                    function (string $path, Model $model) {
                        return LinkBuilder::create('/api/model/' . $model->getId())
                            ->setAttributes([
                                'method' => 'GET'
                            ])
                            ->getLink();
                    }
                )
            ),
        ];
    }
}
```

### ManyModelMapping

```php
<?php

namespace MyProject\Serialization;

use Chubbyphp\Serialization\Mapping\NormalizationLinkMappingInterface;
use Chubbyphp\Serialization\Mapping\NormalizationFieldMappingBuilder;
use Chubbyphp\Serialization\Mapping\NormalizationFieldMappingInterface;
use Chubbyphp\Serialization\Mapping\NormalizationObjectMappingInterface;
use MyProject\Model\ManyModel;

final class ManyModelMapping implements NormalizationObjectMappingInterface
{
    /**
     * @return string
     */
    public function getClass(): string
    {
        return ManyModel::class;
    }

    /**
     * @return string
     */
    public function getNormalizationType(): string
    {
        return 'many-model';
    }

    /**
     * @param string $path
     *
     * @return array<int, NormalizationFieldMappingInterface>
     */
    public function getNormalizationFieldMappings(string $path): array
    {
        return [
            NormalizationFieldMappingBuilder::create('name')->getMapping(),
            NormalizationFieldMappingBuilder::create('value')->getMapping(),
        ];
    }

    /**
     * @param string $path
     *
     * @return array<int, NormalizationFieldMappingInterface>
     */
    public function getNormalizationEmbeddedFieldMappings(string $path): array
    {
        return [];
    }

    /**
     * @param string $path
     *
     * @return array<int, NormalizationLinkMappingInterface>
     */
    public function getNormalizationLinkMappings(string $path): array
    {
        return [];
    }
}
```

### ManyModelMapping

```php
<?php

namespace MyProject\Serialization;

use Chubbyphp\Serialization\Mapping\NormalizationLinkMappingInterface;
use Chubbyphp\Serialization\Mapping\NormalizationFieldMappingBuilder;
use Chubbyphp\Serialization\Mapping\NormalizationFieldMappingInterface;
use Chubbyphp\Serialization\Mapping\NormalizationObjectMappingInterface;
use MyProject\Model\OneModel;

final class OneModelMapping implements NormalizationObjectMappingInterface
{
    /**
     * @return string
     */
    public function getClass(): string
    {
        return OneModel::class;
    }

    /**
     * @return string
     */
    public function getNormalizationType(): string
    {
        return 'one-model';
    }

    /**
     * @param string $path
     *
     * @return array<int, NormalizationFieldMappingInterface>
     */
    public function getNormalizationFieldMappings(string $path): array
    {
        return [
            NormalizationFieldMappingBuilder::create('name')->getMapping(),
            NormalizationFieldMappingBuilder::create('value')->getMapping(),
        ];
    }

    /**
     * @param string $path
     *
     * @return array<int, NormalizationFieldMappingInterface>
     */
    public function getNormalizationEmbeddedFieldMappings(string $path): array
    {
        return [];
    }

    /**
     * @param string $path
     *
     * @return array<int, NormalizationLinkMappingInterface>
     */
    public function getNormalizationLinkMappings(string $path): array
    {
        return [];
    }
}
```

## Model

### Model

```php
<?php

namespace MyProject\Model;

final class Model
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var OneModel|null
     */
    private $one;

    /**
     * @var AbstractManyModel[]
     */
    private $manies;

    public function __construct()
    {
        $this->id = 'ebac0dd9-8eca-4eb9-9fac-aeef65c5c59a';
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return self
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return OneModel|null
     */
    public function getOne()
    {
        return $this->one;
    }

    /**
     * @param OneModel|null $one
     *
     * @return self
     */
    public function setOne(OneModel $one = null)
    {
        $this->one = $one;

        return $this;
    }

    /**
     * @return AbstractManyModel[]
     */
    public function getManies(): array
    {
        return $this->manies;
    }

    /**
     * @param AbstractManyModel[] $manies
     *
     * @return self
     */
    public function setManies(array $manies): self
    {
        $this->manies = $manies;

        return $this;
    }
}
```

### AbstractManyModel

```php
<?php

namespace MyProject\Model;

abstract class AbstractManyModel
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return self
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }
}
```

### ManyModel

```php
<?php

namespace MyProject\Model;

final class ManyModel extends AbstractManyModel
{
    /**
     * @var string
     */
    private $value;

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @param string $value
     *
     * @return self
     */
    public function setValue(string $value): self
    {
        $this->value = $value;

        return $this;
    }
}
```

### OneModel

```php
<?php

namespace MyProject\Model;

final class OneModel
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $value;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return self
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @param string $value
     *
     * @return self
     */
    public function setValue(string $value): self
    {
        $this->value = $value;

        return $this;
    }
}
```
