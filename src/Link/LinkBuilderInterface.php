<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Link;

use Psr\Link\LinkInterface;

interface LinkBuilderInterface
{
    public static function create(string $href): self;

    /**
     * @param array<int, string> $rels
     */
    public function setRels(array $rels): self;

    /**
     * @param array<string, array<string, string>> $attributes
     */
    public function setAttributes(array $attributes): self;

    public function getLink(): LinkInterface;
}
