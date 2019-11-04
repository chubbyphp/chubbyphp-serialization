<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Link;

use Psr\Link\LinkInterface;

final class LinkBuilder implements LinkBuilderInterface
{
    /**
     * @var string
     */
    private $href;

    /**
     * @var string[]
     */
    private $rels;

    /**
     * @var array
     */
    private $attributes;

    private function __construct()
    {
    }

    public static function create(string $href): LinkBuilderInterface
    {
        $self = new self();
        $self->href = $href;
        $self->rels = [];
        $self->attributes = [];

        return $self;
    }

    /**
     * @param string[] $rels
     */
    public function setRels(array $rels): LinkBuilderInterface
    {
        $this->rels = $rels;

        return $this;
    }

    public function setAttributes(array $attributes): LinkBuilderInterface
    {
        $this->attributes = $attributes;

        return $this;
    }

    public function getLink(): LinkInterface
    {
        return new Link($this->href, $this->rels, $this->attributes);
    }
}
