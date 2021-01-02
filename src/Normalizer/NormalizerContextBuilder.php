<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Normalizer;

use Psr\Http\Message\ServerRequestInterface;

final class NormalizerContextBuilder
{
    /**
     * @var array<string, mixed>
     */
    private array $attributes = [];

    private ?ServerRequestInterface $request = null;

    private function __construct()
    {
    }

    public static function create(): self
    {
        return new self();
    }

    /**
     * @param array<string, mixed> $attributes
     */
    public function setAttributes(array $attributes): self
    {
        $this->attributes = $attributes;

        return $this;
    }

    public function setRequest(?ServerRequestInterface $request = null): self
    {
        $this->request = $request;

        return $this;
    }

    public function getContext(): NormalizerContextInterface
    {
        return new NormalizerContext($this->request, $this->attributes);
    }
}
