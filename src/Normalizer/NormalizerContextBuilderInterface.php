<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Normalizer;

interface NormalizerContextBuilderInterface
{
    /**
     * @return NormalizerContextBuilderInterface
     */
    public static function create(): NormalizerContextBuilderInterface;

    /**
     * @param string[] $groups
     *
     * @return self
     */
    public function setGroups(array $groups): self;

    /**
     * @return NormalizerContextInterface
     */
    public function getContext(): NormalizerContextInterface;
}
