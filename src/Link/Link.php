<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Link;

use Psr\Link\LinkInterface;

final class Link implements LinkInterface
{
    /**
     * @param array<int, string>   $rels
     * @param array<string, mixed> $attributes
     */
    public function __construct(private string $href, private array $rels, private array $attributes)
    {
    }

    public function getHref(): string
    {
        return $this->href;
    }

    public function isTemplated(): bool
    {
        return str_contains($this->href, '{');
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
