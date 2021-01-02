<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Mapping;

use Chubbyphp\Serialization\Normalizer\LinkNormalizerInterface;
use Chubbyphp\Serialization\Policy\NullPolicy;
use Chubbyphp\Serialization\Policy\PolicyInterface;

final class NormalizationLinkMapping implements NormalizationLinkMappingInterface
{
    private string $name;

    private LinkNormalizerInterface $linkNormalizer;

    private PolicyInterface $policy;

    public function __construct(
        string $name,
        LinkNormalizerInterface $linkNormalizer,
        ?PolicyInterface $policy = null
    ) {
        $this->name = $name;
        $this->linkNormalizer = $linkNormalizer;
        $this->policy = $policy ?? new NullPolicy();
    }

    public function getName(): string
    {
        return $this->name;
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
