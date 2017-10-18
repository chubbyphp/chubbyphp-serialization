<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Normalizer;

final class NormalizerContextBuilder implements NormalizerContextBuilderInterface
{
    /**
     * @var string[]
     */
    private $groups;

    private function __construct()
    {
    }

    /**
     * @return NormalizerContextBuilderInterface
     */
    public static function create(): NormalizerContextBuilderInterface
    {
        $self = new self();
        $self->groups = [];

        return $self;
    }

    /**
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
     * @return NormalizerContextInterface
     */
    public function getContext(): NormalizerContextInterface
    {
        return new NormalizerContext($this->groups);
    }
}
