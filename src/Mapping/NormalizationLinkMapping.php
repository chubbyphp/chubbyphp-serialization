<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Mapping;

use Chubbyphp\Serialization\Normalizer\LinkNormalizerInterface;

final class NormalizationLinkMapping implements NormalizationLinkMappingInterface
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

    /**
     * @param string                  $name
     * @param array                   $groups
     * @param LinkNormalizerInterface $linkNormalizer
     * @param object|string|null      $permission
     */
    public function __construct(
        string $name,
        array $groups,
        LinkNormalizerInterface $linkNormalizer,
        $permission = null
    ) {
        $this->name = $name;
        $this->groups = $groups;
        $this->linkNormalizer = $linkNormalizer;
        $this->permission = $permission;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string[]
     */
    public function getGroups(): array
    {
        return $this->groups;
    }

    /**
     * @return LinkNormalizerInterface
     */
    public function getLinkNormalizer(): LinkNormalizerInterface
    {
        return $this->linkNormalizer;
    }

    /**
     * @return object|string|null
     */
    public function getPermission()
    {
        return $this->permission;
    }
}
