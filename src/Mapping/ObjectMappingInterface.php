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
     * @return FieldMappingInterface[]
     */
    public function getFieldMappings(): array;

    /**
     * @return FieldMappingInterface[]
     */
    public function getEmbeddedFieldMappings(): array;
}
