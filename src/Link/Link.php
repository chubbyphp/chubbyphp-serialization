<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Link;

use Psr\Link\LinkInterface;

final class Link implements LinkInterface
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

    /**
     * @param string[] $rels
     */
    public function __construct(string $href, array $rels, array $attributes)
    {
        $this->href = $href;
        $this->rels = $rels;
        $this->attributes = $attributes;
    }

    public function getHref(): string
    {
        return $this->href;
    }

    public function isTemplated(): bool
    {
        return false !== strpos($this->href, '{');
    }

    /**
     * @return string[]
     */
    public function getRels(): array
    {
        return $this->rels;
    }

    public function getAttributes(): array
    {
        return $this->attributes;
    }
}
