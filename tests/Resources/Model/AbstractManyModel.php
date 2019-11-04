<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization\Resources\Model;

abstract class AbstractManyModel
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    private $address;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function setAddress(string $address): void
    {
        $this->address = $address;
    }
}
