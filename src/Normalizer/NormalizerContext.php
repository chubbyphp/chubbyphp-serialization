<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Normalizer;

use Psr\Http\Message\ServerRequestInterface;

final class NormalizerContext implements NormalizerContextInterface
{
    /**
     * @param array<string, mixed> $attributes
     */
    public function __construct(private ?ServerRequestInterface $request = null, private array $attributes = []) {}

    public function getRequest(): ?ServerRequestInterface
    {
        return $this->request;
    }

    /**
     * @return array<string, mixed>
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * @return mixed
     */
    public function getAttribute(string $name, mixed $default = null)
    {
        if (isset($this->attributes[$name])) {
            return $this->attributes[$name];
        }

        return $default;
    }

    /**
     * @param array<string, mixed> $attributes
     */
    public function withAttributes(array $attributes): NormalizerContextInterface
    {
        $context = clone $this;
        $context->attributes = $attributes;

        return $context;
    }

    public function withAttribute(string $name, mixed $value): NormalizerContextInterface
    {
        $context = clone $this;
        $context->attributes[$name] = $value;

        return $context;
    }
}
