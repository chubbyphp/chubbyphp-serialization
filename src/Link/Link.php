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
     * @var array<int, string>
     */
    private $rels;

    /**
     * @var array<string, mixed>
     */
    private $attributes;

    /**
     * @param array<int, string>   $rels
     * @param array<string, mixed> $attributes
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
     * @return array<int, string>
     */
    public function getRels(): array
    {
        return $this->rels;
    }

    /**
     * @return array<string, mixed>
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }
}
