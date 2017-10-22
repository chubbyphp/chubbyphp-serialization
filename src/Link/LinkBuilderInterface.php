<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Link;

use Psr\Link\LinkInterface;

interface LinkBuilderInterface
{
    /**
     * @param string $href
     *
     * @return self
     */
    public static function create(string $href): self;

    /**
     * @param string[] $rels
     * @return self
     */
    public function setRels(array $rels): self;

    /**
     * @param array $attributes
     * @return self
     */
    public function setAttributes(array $attributes): self;

    /**
     * @return LinkInterface
     */
    public function getLink(): LinkInterface;
}
