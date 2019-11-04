<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization\Resources\Mapping;

use Chubbyphp\Serialization\Mapping\NormalizationFieldMappingBuilder;
use Chubbyphp\Serialization\Mapping\NormalizationFieldMappingInterface;
use Chubbyphp\Serialization\Mapping\NormalizationLinkMappingInterface;
use Chubbyphp\Serialization\Mapping\NormalizationObjectMappingInterface;
use Chubbyphp\Tests\Serialization\Resources\Model\ManyModel;

final class ManyModelMapping implements NormalizationObjectMappingInterface
{
    public function getClass(): string
    {
        return ManyModel::class;
    }

    public function getNormalizationType(): string
    {
        return 'many-model';
    }

    /**
     * @return NormalizationFieldMappingInterface[]
     */
    public function getNormalizationFieldMappings(string $path): array
    {
        return [
            NormalizationFieldMappingBuilder::create('name')->getMapping(),
            NormalizationFieldMappingBuilder::create('value')->getMapping(),
        ];
    }

    /**
     * @return NormalizationFieldMappingInterface[]
     */
    public function getNormalizationEmbeddedFieldMappings(string $path): array
    {
        return [];
    }

    /**
     * @return NormalizationLinkMappingInterface[]
     */
    public function getNormalizationLinkMappings(string $path): array
    {
        return [];
    }
}
