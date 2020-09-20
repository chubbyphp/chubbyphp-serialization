<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Link;

use Psr\Link\LinkInterface;

final class LinkBuilder
{
    /**
     * @var string
     */
    private $href;

    /**
     * @var array<int, string>
     */
    private $rels;

    /**
     * @var array<string, mixed>
     */
    private $attributes;

    private function __construct()
    {
    }

    public static function create(string $href): self
    {
        $self = new self();
        $self->href = $href;
        $self->rels = [];
        $self->attributes = [];

        return $self;
    }

    /**
     * @param array<int, string> $rels
     */
    public function setRels(array $rels): self
    {
        $this->rels = $rels;

        return $this;
    }

    /**
     * @param array<string, mixed> $attributes
     */
    public function setAttributes(array $attributes): self
    {
        $this->attributes = $attributes;

        return $this;
    }

    public function getLink(): LinkInterface
    {
        return new Link($this->href, $this->rels, $this->attributes);
    }
}
