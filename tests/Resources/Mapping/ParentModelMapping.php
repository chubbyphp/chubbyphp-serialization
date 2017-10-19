<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization\Resources\Mapping;

use Chubbyphp\Serialization\Accessor\PropertyAccessor;
use Chubbyphp\Serialization\Mapping\NormalizationLinkMappingInterface;
use Chubbyphp\Serialization\Normalizer\CollectionFieldNormalizer;
use Chubbyphp\Serialization\Mapping\NormalizationFieldMappingBuilder;
use Chubbyphp\Serialization\Mapping\NormalizationFieldMappingInterface;
use Chubbyphp\Serialization\Mapping\NormalizationObjectMappingInterface;
use Chubbyphp\Tests\Serialization\Resources\Model\AbstractChildModel;
use Chubbyphp\Tests\Serialization\Resources\Model\ParentModel;

final class ParentModelMapping implements NormalizationObjectMappingInterface
{
    /**
     * @return string
     */
    public function getClass(): string
    {
        return ParentModel::class;
    }

    /**
     * @return string
     */
    public function getNormalizationType(): string
    {
        return 'parent-model';
    }

    /**
     * @param string      $path
     * @param string|null $type
     *
     * @return NormalizationFieldMappingInterface[]
     */
    public function getNormalizationFieldMappings(string $path, string $type = null): array
    {
        return [
            NormalizationFieldMappingBuilder::create('name')->getMapping(),
            NormalizationFieldMappingBuilder::create('children')->setFieldNormalizer(
                new CollectionFieldNormalizer(AbstractChildModel::class, new PropertyAccessor('children'))
            )->getMapping(),
        ];
    }

    /**
     * @param string $path
     *
     * @return NormalizationFieldMappingInterface[]
     */
    public function getNormalizationEmbeddedFieldMappings(string $path): array
    {
        return [
            NormalizationFieldMappingBuilder::create('relatedChildren')->setGroups(['related'])->setFieldNormalizer(
                new CollectionFieldNormalizer(AbstractChildModel::class, new PropertyAccessor('children'))
            )->getMapping(),
        ];
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
