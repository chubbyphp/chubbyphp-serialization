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
     * @var array<int, string>
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
     * @param array<int, string> $groups
     */
    public function __construct(
        string $name,
        array $groups,
        LinkNormalizerInterface $linkNormalizer,
        ?PolicyInterface $policy = null
    ) {
        $this->name = $name;
        $this->groups = $groups;
        $this->linkNormalizer = $linkNormalizer;
        $this->policy = $policy ?? new NullPolicy();
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @deprecated
     *
     * @return array<int, string>
     */
    public function getGroups(): array
    {
        return $this->groups;
    }

    public function getLinkNormalizer(): LinkNormalizerInterface
    {
        return $this->linkNormalizer;
    }

    public function getPolicy(): PolicyInterface
    {
        return $this->policy;
    }
}
