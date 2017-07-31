<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization\Resources;

use Chubbyphp\Serialization\Mapping\FieldMapping;
use Chubbyphp\Serialization\Mapping\FieldMappingInterface;
use Chubbyphp\Serialization\Mapping\ObjectMappingInterface;

final class ModelMapping implements ObjectMappingInterface
{
    /**
     * @return string
     */
    public function getClass(): string
    {
        return Model::class;
    }

    /**
     * @return FieldMappingInterface[]
     */
    public function getFieldMappings(): array
    {
        return [
            new FieldMapping('name')
        ];
    }

    /**
     * @return FieldMappingInterface[]
     */
    public function getEmbeddedFieldMappings(): array
    {
        return [
            new FieldMapping('name')
        ];
    }
}
