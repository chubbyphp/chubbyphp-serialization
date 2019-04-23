<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Mapping;

use Chubbyphp\Serialization\Normalizer\LinkNormalizerInterface;

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
     * @var object|string|null
     */
    private $permission;

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
     * @param object|string|null $permission
     *
     * @return NormalizationLinkMappingBuilderInterface
     */
    public function setPermission($permission): NormalizationLinkMappingBuilderInterface
    {
        $this->permission = $permission;

        return $this;
    }

    /**
     * @return NormalizationLinkMappingInterface
     */
    public function getMapping(): NormalizationLinkMappingInterface
    {
        return new NormalizationLinkMapping($this->name, $this->groups, $this->linkNormalizer, $this->permission);
    }
}
