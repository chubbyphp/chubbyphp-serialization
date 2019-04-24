<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Normalizer;

use Psr\Http\Message\ServerRequestInterface;

final class NormalizerContextBuilder implements NormalizerContextBuilderInterface
{
    /**
     * @deprecated
     *
     * @var string[]
     */
    private $groups = [];

    /**
     * @var array
     */
    private $attributes;

    /**
     * @var ServerRequestInterface|null
     */
    private $request;

    private function __construct()
    {
    }

    /**
     * @return NormalizerContextBuilderInterface
     */
    public static function create(): NormalizerContextBuilderInterface
    {
        return new self();
    }

    /**
     * @deprecated
     *
     * @param string[] $groups
     *
     * @return NormalizerContextBuilderInterface
     */
    public function setGroups(array $groups): NormalizerContextBuilderInterface
    {
        $this->groups = $groups;

        return $this;
    }

    /**
     * @param array $attributes
     *
     * @return NormalizerContextBuilderInterface
     */
    public function setAttributes(array $attributes): NormalizerContextBuilderInterface
    {
        $this->attributes = $attributes;

        return $this;
    }

    /**
     * @param ServerRequestInterface|null $request
     *
     * @return NormalizerContextBuilderInterface
     */
    public function setRequest(ServerRequestInterface $request = null): NormalizerContextBuilderInterface
    {
        $this->request = $request;

        return $this;
    }

    /**
     * @return NormalizerContextInterface
     */
    public function getContext(): NormalizerContextInterface
    {
        return new NormalizerContext($this->groups, $this->request, $this->attributes);
    }
}
