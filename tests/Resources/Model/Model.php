<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization\Resources\Model;

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
