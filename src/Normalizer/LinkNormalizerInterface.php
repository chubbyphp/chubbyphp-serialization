<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Normalizer;

use Chubbyphp\Serialization\SerializerLogicException;

interface LinkNormalizerInterface
{
    /**
     * @param string                     $path
     * @param object                     $object
     * @param NormalizerContextInterface $context
     *
     * @throws SerializerLogicException
     *
     * @return array|null
     */
    public function normalizeLink(string $path, $object, NormalizerContextInterface $context);
}
