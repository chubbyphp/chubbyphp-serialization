<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization\Resources\Model;

final class Model
{
    private string $id;

    private ?string $name = null;

    private ?string $additionalInfo = null;

    private string $hiddenProperty;

    private ?OneModel $one = null;

    /**
     * @var AbstractManyModel[]
     */
    private ?array $manies = null;

    public function __construct()
    {
        $this->id = 'ebac0dd9-8eca-4eb9-9fac-aeef65c5c59a';
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getAdditionalInfo(): string
    {
        return $this->additionalInfo;
    }

    public function setAdditionalInfo(string $additionalInfo): self
    {
        $this->additionalInfo = $additionalInfo;

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
     */
    public function setManies(array $manies): self
    {
        $this->manies = $manies;

        return $this;
    }
}
