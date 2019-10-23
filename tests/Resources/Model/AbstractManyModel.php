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
    public function getAddress(): string
    {
        return $this->address;
    }

    /**
     * @param string $address
     */
    public function setAddress(string $address): void
    {
        $this->address = $address;
    }
}
