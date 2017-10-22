<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Mapping;

use Chubbyphp\Serialization\Normalizer\LinkNormalizerInterface;

interface NormalizationLinkMappingBuilderInterface
{
    /**
     * @param string                  $name
     * @param LinkNormalizerInterface $linkNormalizer
     *
     * @return self
     */
    public static function create(string $name, LinkNormalizerInterface $linkNormalizer): self;

    /**
     * @param array $groups
     *
     * @return self
     */
    public function setGroups(array $groups): self;

    /**
     * @return NormalizationLinkMappingInterface
     */
    public function getMapping(): NormalizationLinkMappingInterface;
}
