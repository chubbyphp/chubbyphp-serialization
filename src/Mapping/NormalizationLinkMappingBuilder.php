<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Mapping;

use Chubbyphp\Serialization\Normalizer\LinkNormalizerInterface;
use Chubbyphp\Serialization\Policy\PolicyInterface;
use Chubbyphp\Serialization\Policy\NullPolicy;

final class NormalizationLinkMappingBuilder implements NormalizationLinkMappingBuilderInterface
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var array
     */
    private $groups;

    /**
     * @var LinkNormalizerInterface
     */
    private $linkNormalizer;

    /**
     * @var PolicyInterface|null
     */
    private $policy;

    private function __construct()
    {
    }

    /**
     * @param string                  $name
     * @param LinkNormalizerInterface $linkNormalizer
     *
     * @return NormalizationLinkMappingBuilderInterface
     */
    public static function create(
        string $name,
        LinkNormalizerInterface $linkNormalizer
    ): NormalizationLinkMappingBuilderInterface {
        $self = new self();
        $self->name = $name;
        $self->groups = [];
        $self->linkNormalizer = $linkNormalizer;

        return $self;
    }

    /**
     * @deprecated
     *
     * @param array $groups
     *
     * @return NormalizationLinkMappingBuilderInterface
     */
    public function setGroups(array $groups): NormalizationLinkMappingBuilderInterface
    {
        $this->groups = $groups;

        return $this;
    }

    /**
     * @param PolicyInterface $policy
     *
     * @return NormalizationLinkMappingBuilderInterface
     */
    public function setPolicy(PolicyInterface $policy): NormalizationLinkMappingBuilderInterface
    {
        $this->policy = $policy;

        return $this;
    }

    /**
     * @return NormalizationLinkMappingInterface
     */
    public function getMapping(): NormalizationLinkMappingInterface
    {
        return new NormalizationLinkMapping(
            $this->name,
            $this->groups,
            $this->linkNormalizer,
            $this->policy ?? new NullPolicy()
        );
    }
}
