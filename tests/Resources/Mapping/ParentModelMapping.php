<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization\Resources\Mapping;

use Chubbyphp\Serialization\Accessor\PropertyAccessor;
use Chubbyphp\Serialization\Normalizer\CollectionFieldNormalizer;
use Chubbyphp\Serialization\SerializerRuntimeException;
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
     * @param string      $path
     * @param string|null $type
     *
     * @return callable
     *
     * @throws SerializerRuntimeException
     */
    public function getNormalizationFactory(string $path, string $type = null): callable
    {
        return function () {
            return new ParentModel();
        };
    }

    /**
     * @param string      $path
     * @param string|null $type
     *
     * @return NormalizationFieldMappingInterface[]
     *
     * @throws SerializerRuntimeException
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
}
