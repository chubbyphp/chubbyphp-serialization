<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization\Resources;

final class EmbeddedModel
{
    /**
     * @var string|null
     */
    private $name;

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
}
