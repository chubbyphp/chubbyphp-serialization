<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization\Resources\Model;

final class OneModel
{
    private ?string $name = null;

    private ?string $value = null;

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
