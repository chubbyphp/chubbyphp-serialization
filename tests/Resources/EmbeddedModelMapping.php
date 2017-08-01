<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization\Resources;

use Chubbyphp\Serialization\Mapping\FieldMapping;
use Chubbyphp\Serialization\Mapping\FieldMappingInterface;
use Chubbyphp\Serialization\Mapping\LinkMappingInterface;
use Chubbyphp\Serialization\Mapping\ObjectMappingInterface;

final class EmbeddedModelMapping implements ObjectMappingInterface
{
    /**
     * @return string
     */
    public function getClass(): string
    {
        return EmbeddedModel::class;
    }

    /**
     * @return FieldMappingInterface[]
     */
    public function getFieldMappings(): array
    {
        return [
            new FieldMapping('name'),
        ];
    }

    /**
     * @return FieldMappingInterface[]
     */
    public function getEmbeddedFieldMappings(): array
    {
        return [];
    }

    /**
     * @return LinkMappingInterface[]
     */
    public function getLinkMappings(): array
    {
        return [];
    }
}
