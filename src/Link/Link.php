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
     * @var bool
     */
    private $templated;

    /**
     * @var string[]
     */
    private $rels;

    /**
     * @var array
     */
    private $attributes;

    /**
     * @param string   $href
     * @param bool     $templated
     * @param string[] $rels
     * @param array    $attributes
     */
    public function __construct(string $href, bool $templated, array $rels, array $attributes)
    {
        $this->href = $href;
        $this->templated = $templated;
        $this->rels = $rels;
        $this->attributes = $attributes;
    }

    /**
     * @return string
     */
    public function getHref(): string
    {
        return $this->href;
    }

    /**
     * @return bool
     */
    public function isTemplated(): bool
    {
        return $this->templated;
    }

    /**
     * @return string[]
     */
    public function getRels(): array
    {
        return $this->rels;
    }

    /**
     * @return array
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }
}
