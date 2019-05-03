<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Normalizer;

use Psr\Http\Message\ServerRequestInterface;

/**
 * @method getAttributes(): array
 * @method getAttribute(string $name, $default = null)
 * @method withAttribute(string $name, $value): self
 */
interface NormalizerContextInterface
{
    /**
     * @deprecated
     *
     * @return string[]
     */
    public function getGroups(): array;

    /**
     * @return ServerRequestInterface|null
     */
    public function getRequest();

    /**
     * @return array
     */
    //public function getAttributes(): array;

    /*
     * @param string $name
     * @param mixed  $default
     *
     * @return mixed
     */
    //public function getAttribute(string $name, $default = null);

    /**
     * @param string $name
     * @param mixed  $value
     * @return self
     */
    //public function withAttribute(string $name, $value): self;
}
