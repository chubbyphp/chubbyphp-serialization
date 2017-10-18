<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Normalizer;

interface NormalizerContextInterface
{
    /**
     * @return string[]
     */
    public function getGroups(): array;
}
