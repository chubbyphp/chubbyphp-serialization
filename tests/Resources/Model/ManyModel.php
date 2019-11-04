<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization\Resources\Model;

final class ManyModel extends AbstractManyModel
{
    /**
     * @var string
     */
    private $value;

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
