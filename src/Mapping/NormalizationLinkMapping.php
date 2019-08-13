<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Mapping;

use Chubbyphp\Serialization\Normalizer\LinkNormalizerInterface;
use Chubbyphp\Serialization\Policy\NullPolicy;
use Chubbyphp\Serialization\Policy\PolicyInterface;

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
     * @var PolicyInterface
     */
    private $policy;

    /**
     * @param string                  $name
     * @param array                   $groups
     * @param LinkNormalizerInterface $linkNormalizer
     * @param PolicyInterface|null    $policy
     */
    public function __construct(
        string $name,
        array $groups,
        LinkNormalizerInterface $linkNormalizer,
        PolicyInterface $policy = null
    ) {
        $this->name = $name;
        $this->groups = $groups;
        $this->linkNormalizer = $linkNormalizer;
        $this->policy = $policy ?? new NullPolicy();
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @deprecated
     *
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
     * @return PolicyInterface
     */
    public function getPolicy(): PolicyInterface
    {
        return $this->policy;
    }
}
