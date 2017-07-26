<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Mapping;

interface EmbeddedInterface
{
    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return string
     */
    public function getClass(): string;
}
