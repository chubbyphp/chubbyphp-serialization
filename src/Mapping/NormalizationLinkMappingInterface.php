<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Mapping;

use Chubbyphp\Serialization\Normalizer\LinkNormalizerInterface;
use Chubbyphp\Serialization\Policy\PolicyInterface;

/**
 * @method getPolicy(): PolicyInterface
 */
interface NormalizationLinkMappingInterface
{
    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @deprecated
     *
     * @return string[]
     */
    public function getGroups(): array;

    /**
     * @return LinkNormalizerInterface
     */
    public function getLinkNormalizer(): LinkNormalizerInterface;

    /*
     * @return PolicyInterface
     */
    //public function getPolicy(): PolicyInterface;
}
