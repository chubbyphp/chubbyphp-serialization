<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Normalizer;

use Psr\Http\Message\ServerRequestInterface;

final class NormalizerContextBuilder implements NormalizerContextBuilderInterface
{
    /**
     * @deprecated
     *
     * @var array<int, string>
     */
    private $groups = [];

    /**
     * @var array<mixed>
     */
    private $attributes = [];

    /**
     * @var ServerRequestInterface|null
     */
    private $request;

    private function __construct()
    {
    }

    public static function create(): NormalizerContextBuilderInterface
    {
        return new self();
    }

    /**
     * @deprecated
     *
     * @param array<int, string> $groups
     */
    public function setGroups(array $groups): NormalizerContextBuilderInterface
    {
        $this->groups = $groups;

        return $this;
    }

    /**
     * @param array<mixed> $attributes
     */
    public function setAttributes(array $attributes): NormalizerContextBuilderInterface
    {
        $this->attributes = $attributes;

        return $this;
    }

    public function setRequest(?ServerRequestInterface $request = null): NormalizerContextBuilderInterface
    {
        $this->request = $request;

        return $this;
    }

    public function getContext(): NormalizerContextInterface
    {
        return new NormalizerContext($this->groups, $this->request, $this->attributes);
    }
}
