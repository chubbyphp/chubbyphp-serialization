<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Normalizer;

use Psr\Http\Message\ServerRequestInterface;
use Chubbyphp\Serialization\Policy\GroupPolicy;

final class NormalizerContext implements NormalizerContextInterface
{
    /**
     * @deprecated
     *
     * @var string[]
     */
    private $groups = [];

    /**
     * @var ServerRequestInterface|null
     */
    private $request;

    /**
     * @var array
     */
    private $attributes;

    /**
     * @param string[]                    $groups
     * @param ServerRequestInterface|null $request
     * @param array                       $attributes
     */
    public function __construct(array $groups = [], ServerRequestInterface $request = null, array $attributes = [])
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
     * @return string[]
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
     * @return array
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * @param string $name
     * @param mixed  $default
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
     * @param string $name
     * @param mixed  $value
     * @return NormalizerContextInterface
     */
    public function withAttribute(string $name, $value): NormalizerContextInterface
    {
        $context = clone $this;
        $context->attributes[$name] = $value;

        return $context;
    }
}
