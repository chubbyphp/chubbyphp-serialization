<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Mapping;

use Chubbyphp\Serialization\Normalizer\LinkNormalizerInterface;
use Chubbyphp\Serialization\Policy\PolicyInterface;

/**
 * @method NormalizationLinkMappingBuilderInterface setPolicy(PolicyInterface $policy)
 */
interface NormalizationLinkMappingBuilderInterface
{
    public static function create(string $name, LinkNormalizerInterface $linkNormalizer): self;

    /**
     * @deprecated
     *
     * @param array<int, string> $groups
     */
    public function setGroups(array $groups): self;

    /**
     * @param PolicyInterface $policy
     *
     * @return self
     */
    //public function setPolicy(PolicyInterface $policy): self;

    public function getMapping(): NormalizationLinkMappingInterface;
}
