<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Normalizer;

use Psr\Http\Message\ServerRequestInterface;

/**
 * @method NormalizerContextBuilderInterface setAttributes(array $attributes)
 */
interface NormalizerContextBuilderInterface
{
    public static function create(): self;

    /**
     * @deprecated
     *
     * @param array<int, string> $groups
     */
    public function setGroups(array $groups): self;

    // /**
    //  * @param array<mixed> $attributes
    //  *
    //  * @return NormalizerContextBuilderInterface
    //  */
    // public function setAttributes(array $attributes): self;

    public function setRequest(?ServerRequestInterface $request = null): self;

    public function getContext(): NormalizerContextInterface;
}
