<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Mapping;

interface ObjectMappingInterface
{
    /**
     * @return string
     */
    public function getClass(): string;

    /**
     * @return array
     */
    public function getFields(): array;

    /**
     * @return EmbeddedInterface[]
     */
    public function getEmbeddeds(): array;

    /**
     * @return LinkInterface[]
     */
    public function getLinks(): array;
}
