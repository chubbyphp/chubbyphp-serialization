<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Normalizer;

use Psr\Http\Message\ServerRequestInterface;

final class NormalizerContext implements NormalizerContextInterface
{
    /**
     * @var ServerRequestInterface|null
     */
    private $request;

    /**
     * @var array<string, mixed>
     */
    private $attributes;

    /**
     * @param array<string, mixed> $attributes
     */
    public function __construct(?ServerRequestInterface $request = null, array $attributes = [])
    {
        $this->request = $request;
        $this->attributes = $attributes;
    }

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
     * @param mixed $default
     *
     * @return mixed
     */
    public function getAttribute(string $name, $default = null)
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

    /**
     * @param mixed $value
     */
    public function withAttribute(string $name, $value): NormalizerContextInterface
    {
        $context = clone $this;
        $context->attributes[$name] = $value;

        return $context;
    }
}
