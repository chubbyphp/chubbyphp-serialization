<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization\Resources\Mapping;

use Chubbyphp\Serialization\Link\LinkBuilder;
use Chubbyphp\Serialization\Mapping\NormalizationFieldMappingBuilder;
use Chubbyphp\Serialization\Mapping\NormalizationFieldMappingInterface;
use Chubbyphp\Serialization\Mapping\NormalizationLinkMapping;
use Chubbyphp\Serialization\Mapping\NormalizationLinkMappingInterface;
use Chubbyphp\Serialization\Mapping\NormalizationObjectMappingInterface;
use Chubbyphp\Serialization\Normalizer\CallbackLinkNormalizer;
use Chubbyphp\Serialization\Policy\AndPolicy;
use Chubbyphp\Serialization\Policy\CallbackPolicyIncludingPath;
use Chubbyphp\Serialization\Policy\GroupPolicy;
use Chubbyphp\Serialization\Policy\NullPolicy;
use Chubbyphp\Serialization\Policy\OrPolicy;
use Chubbyphp\Tests\Serialization\Resources\Model\Model;

final class ModelMapping implements NormalizationObjectMappingInterface
{
    public function getClass(): string
    {
        return Model::class;
    }

    public function getNormalizationType(): string
    {
        return 'model';
    }

    /**
     * @return array<int, NormalizationFieldMappingInterface>
     */
    public function getNormalizationFieldMappings(string $path, string $type = null): array
    {
        return [
            NormalizationFieldMappingBuilder::create('id')
                ->setPolicy(new AndPolicy([
                    new NullPolicy(),
                    new OrPolicy([
                        new CallbackPolicyIncludingPath(function () {
                            return false;
                        }),
                        new NullPolicy(),
                    ]),
                ]))
                ->getMapping(),
            NormalizationFieldMappingBuilder::create('name')
                ->setPolicy(new GroupPolicy([]))
                ->getMapping(),
            NormalizationFieldMappingBuilder::create('additionalInfo')
                ->setPolicy(new GroupPolicy(['additionalInfo']))
                ->getMapping(),
            NormalizationFieldMappingBuilder::create('hiddenProperty')
                ->setPolicy(new AndPolicy([
                    new NullPolicy(),
                    new OrPolicy([
                        new CallbackPolicyIncludingPath(function () {
                            return false;
                        }),
                    ]),
                ]))
                ->getMapping(),
            NormalizationFieldMappingBuilder::createEmbedOne('one')->getMapping(),
            NormalizationFieldMappingBuilder::createEmbedMany('manies')->getMapping(),
        ];
    }

    /**
     * @return array<int, NormalizationFieldMappingInterface>
     */
    public function getNormalizationEmbeddedFieldMappings(string $path): array
    {
        return [];
    }

    /**
     * @return array<int, NormalizationLinkMappingInterface>
     */
    public function getNormalizationLinkMappings(string $path): array
    {
        return [
            new NormalizationLinkMapping(
                'self',
                new CallbackLinkNormalizer(
                    function (string $path, Model $model) {
                        return LinkBuilder::create('/api/model/'.$model->getId())
                            ->setAttributes([
                                'method' => 'GET',
                            ])
                            ->getLink()
                        ;
                    }
                )
            ),
        ];
    }
}
