<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Normalizer;

use Psr\Http\Message\ServerRequestInterface;

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
     * @param ServerRequestInterface $request
     * @return NormalizerContextBuilderInterface
     */
    public function setRequest(ServerRequestInterface $request): self;

    /**
     * @return NormalizerContextInterface
     */
    public function getContext(): NormalizerContextInterface;
}
