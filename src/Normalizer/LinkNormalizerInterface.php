<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Normalizer;

use Chubbyphp\Serialization\SerializerLogicException;

interface LinkNormalizerInterface
{
    /**
     * @throws SerializerLogicException
     *
     * @return array<string, mixed>
     */
    public function normalizeLink(string $path, object $object, NormalizerContextInterface $context): array;
}
