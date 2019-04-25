<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Normalizer;

use Psr\Http\Message\ServerRequestInterface;

/**
 * @method setAttributes(array $attributes): self
 */
interface NormalizerContextBuilderInterface
{
    /**
     * @return self
     */
    public static function create(): self;

    /**
     * @deprecated
     *
     * @param string[] $groups
     *
     * @return self
     */
    public function setGroups(array $groups): self;

    /**
     * @param array $attributes
     *
     * @return NormalizerContextBuilderInterface
     */
    //public function setAttributes(array $attributes): self;

    /**
     * @param ServerRequestInterface|null $request
     *
     * @return self
     */
    public function setRequest(ServerRequestInterface $request = null): self;

    /**
     * @return NormalizerContextInterface
     */
    public function getContext(): NormalizerContextInterface;
}
