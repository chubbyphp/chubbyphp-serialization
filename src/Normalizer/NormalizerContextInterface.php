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
     * @return mixed
     */
    public function getAttribute(string $name, mixed $default = null);

    public function withAttribute(string $name, mixed $value): self;
}
