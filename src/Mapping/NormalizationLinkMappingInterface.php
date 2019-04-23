<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Mapping;

use Chubbyphp\Serialization\Normalizer\LinkNormalizerInterface;

interface NormalizationLinkMappingInterface
{
    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return string[]
     */
    public function getGroups(): array;

    /**
     * @return LinkNormalizerInterface
     */
    public function getLinkNormalizer(): LinkNormalizerInterface;

    /*
     * @return object|string|null
     */
    //public function getPermission();
}
