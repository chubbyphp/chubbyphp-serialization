<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Normalizer;

use Chubbyphp\Serialization\SerializerLogicException;

interface LinkNormalizerInterface
{
    /**
     * @param object $object
     *
     * @throws SerializerLogicException
     *
     * @return array<mixed>|null
     */
    public function normalizeLink(string $path, $object, NormalizerContextInterface $context);
}
