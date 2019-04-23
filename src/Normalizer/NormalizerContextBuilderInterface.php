<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Normalizer;

use Psr\Http\Message\ServerRequestInterface;

interface NormalizerContextBuilderInterface
{
    /**
     * @return self
     */
    public static function create(): self;

    /**
     * @param string[] $groups
     *
     * @return self
     */
    public function setGroups(array $groups): self;

    /**
     * @param ServerRequestInterface|null $request
     *
     * @return self
     */
    public function setRequest(ServerRequestInterface $request = null): self;

    /**
     * @param object|string|null $role
     *
     * @return self
     */
    //public function setRole($role): self;

    /**
     * @return NormalizerContextInterface
     */
    public function getContext(): NormalizerContextInterface;
}
