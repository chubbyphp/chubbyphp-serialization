<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Normalizer;

use Chubbyphp\Serialization\Policy\GroupPolicy;
use Psr\Http\Message\ServerRequestInterface;

final class NormalizerContext implements NormalizerContextInterface
{
    /**
     * @deprecated
     *
     * @var array<int, string>
     */
    private $groups = [];

    /**
     * @var ServerRequestInterface|null
     */
    private $request;

    /**
     * @var array<mixed>
     */
    private $attributes;

    /**
     * @param array<int, string> $groups
     * @param array<mixed>       $attributes
     */
    public function __construct(array $groups = [], ?ServerRequestInterface $request = null, array $attributes = [])
    {
        if ([] !== $groups) {
            @trigger_error(sprintf('groups are deprecated, use "%s" instead', GroupPolicy::class), E_USER_DEPRECATED);
        }

        $this->groups = $groups;
        $this->request = $request;
        $this->attributes = $attributes;
    }

    /**
     * @deprecated
     *
     * @return array<int, string>
     */
    public function getGroups(): array
    {
        return $this->groups;
    }

    /**
     * @return ServerRequestInterface|null
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @return array<mixed>
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
     * @param mixed $value
     */
    public function withAttribute(string $name, $value): NormalizerContextInterface
    {
        $context = clone $this;
        $context->attributes[$name] = $value;

        return $context;
    }
}
