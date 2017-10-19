<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization\Resources\Mapping;

use Chubbyphp\Serialization\Mapping\NormalizationLinkMappingInterface;
use Chubbyphp\Serialization\Mapping\NormalizationFieldMappingBuilder;
use Chubbyphp\Serialization\Mapping\NormalizationFieldMappingInterface;
use Chubbyphp\Serialization\Mapping\NormalizationObjectMappingInterface;
use Chubbyphp\Tests\Serialization\Resources\Model\ChildModel;

final class ChildModelMapping implements NormalizationObjectMappingInterface
{
    /**
     * @return string
     */
    public function getClass(): string
    {
        return ChildModel::class;
    }

    /**
     * @return string
     */
    public function getNormalizationType(): string
    {
        return 'child-model';
    }

    /**
     * @param string $path
     *
     * @return NormalizationFieldMappingInterface[]
     */
    public function getNormalizationFieldMappings(string $path): array
    {
        return [
            NormalizationFieldMappingBuilder::create('name')->setGroups(['related'])->getMapping(),
            NormalizationFieldMappingBuilder::create('value')->setGroups(['related'])->getMapping(),
        ];
    }

    /**
     * @param string $path
     *
     * @return NormalizationFieldMappingInterface[]
     */
    public function getNormalizationEmbeddedFieldMappings(string $path): array
    {
        return [];
    }

    /**
     * @param string $path
     *
     * @return NormalizationLinkMappingInterface[]
     */
    public function getNormalizationLinkMappings(string $path): array
    {
        return [];
    }
}
