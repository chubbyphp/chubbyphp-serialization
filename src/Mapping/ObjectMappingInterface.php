<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Mapping;

interface ObjectMappingInterface
{
    /**
     * @return string
     */
    public function getClass(): string;
}
