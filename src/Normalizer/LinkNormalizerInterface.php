<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Normalizer;

interface LinkNormalizerInterface
{
    /**
     * @param string                     $path
     * @param object                     $object
     * @param NormalizerContextInterface $context
     *
     * @return array|null
     */
    public function normalizeLink(string $path, $object, NormalizerContextInterface $context);
}
