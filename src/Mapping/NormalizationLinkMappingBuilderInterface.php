<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Mapping;

use Chubbyphp\Serialization\Normalizer\LinkNormalizerInterface;
use Chubbyphp\Serialization\Policy\PolicyInterface;

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
     * @deprecated
     *
     * @param array $groups
     *
     * @return self
     */
    public function setGroups(array $groups): self;

    /**
     * @param PolicyInterface $policy
     *
     * @return self
     */
    //public function setPolicy(PolicyInterface $policy): self;

    /**
     * @return NormalizationLinkMappingInterface
     */
    public function getMapping(): NormalizationLinkMappingInterface;
}
