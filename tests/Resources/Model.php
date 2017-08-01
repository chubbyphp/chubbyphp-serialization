<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization\Resources;

final class Model
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var string|null
     */
    private $name;

    /**
     * @var EmbeddedModel
     */
    private $embeddedModel;

    /**
     * @var EmbeddedModel[]
     */
    private $embeddedModels;

    /**
     * Model constructor.
     *
     * @param string $id
     */
    public function __construct(string $id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return null|string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param null|string $name
     *
     * @return self
     */
    public function setName(string $name = null): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return EmbeddedModel
     */
    public function getEmbeddedModel(): EmbeddedModel
    {
        return $this->embeddedModel;
    }

    /**
     * @param EmbeddedModel $embeddedModel
     */
    public function setEmbeddedModel(EmbeddedModel $embeddedModel)
    {
        $this->embeddedModel = $embeddedModel;
    }

    /**
     * @return EmbeddedModel[]
     */
    public function getEmbeddedModels(): array
    {
        return $this->embeddedModels;
    }

    /**
     * @param EmbeddedModel[] $embeddedModels
     */
    public function setEmbeddedModels(array $embeddedModels)
    {
        $this->embeddedModels = $embeddedModels;
    }
}
