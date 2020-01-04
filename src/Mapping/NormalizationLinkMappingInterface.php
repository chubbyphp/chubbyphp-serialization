<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Mapping;

use Chubbyphp\Serialization\Normalizer\LinkNormalizerInterface;
use Chubbyphp\Serialization\Policy\PolicyInterface;

/**
 * @method PolicyInterface getPolicy()
 */
interface NormalizationLinkMappingInterface
{
    public function getName(): string;

    /**
     * @deprecated
     *
     * @return array<int, string>
     */
    public function getGroups(): array;

    public function getLinkNormalizer(): LinkNormalizerInterface;

    /*
     * @return PolicyInterface
     */
    //public function getPolicy(): PolicyInterface;
}
