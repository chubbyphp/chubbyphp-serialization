<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Mapping;

use Chubbyphp\Serialization\Normalizer\LinkNormalizerInterface;
use Chubbyphp\Serialization\Policy\PolicyInterface;

interface NormalizationLinkMappingInterface
{
    public function getName(): string;

    public function getLinkNormalizer(): LinkNormalizerInterface;

    public function getPolicy(): PolicyInterface;
}
