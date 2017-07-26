<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization\Resources;

final class Model
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
     */
    public function setName($name)
    {
        $this->name = $name;
    }
}
