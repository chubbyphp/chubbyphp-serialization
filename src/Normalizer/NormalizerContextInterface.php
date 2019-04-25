<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Normalizer;

use Psr\Http\Message\ServerRequestInterface;

/**
 * @method getAttribute(string $name, $default = null)
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

    /*
     * @param string $name
     * @param mixed  $default
     *
     * @return mixed
     */
    //public function getAttribute(string $name, $default = null);
}
