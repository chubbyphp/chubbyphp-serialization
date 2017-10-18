<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization\Resources\Model;

final class ParentModel
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var ChildModel[]
     */
    private $children;

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
     * @return ChildModel[]
     */
    public function getChildren(): array
    {
        return $this->children;
    }

    /**
     * @param ChildModel[] $children
     *
     * @return self
     */
    public function setChildren(array $children): self
    {
        $this->children = $children;

        return $this;
    }
}
