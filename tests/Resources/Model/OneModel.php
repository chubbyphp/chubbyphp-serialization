<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization\Resources\Model;

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

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function setValue(string $value): self
    {
        $this->value = $value;

        return $this;
    }
}
