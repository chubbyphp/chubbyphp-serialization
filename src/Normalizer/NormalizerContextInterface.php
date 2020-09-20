<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Normalizer;

use Psr\Http\Message\ServerRequestInterface;

interface NormalizerContextInterface
{
    public function getRequest(): ?ServerRequestInterface;

    /**
     * @return array<string, mixed>
     */
    public function getAttributes(): array;

    /**
     * @param mixed $default
     *
     * @return mixed
     */
    public function getAttribute(string $name, $default = null);

    /**
     * @param mixed $value
     */
    public function withAttribute(string $name, $value): self;
}
