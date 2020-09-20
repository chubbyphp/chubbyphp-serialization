<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Normalizer;

use Chubbyphp\Serialization\SerializerLogicException;

interface NormalizerInterface
{
    /**
     * @throws SerializerLogicException
     *
     * @return array<string, array|string|float|int|bool|null>
     */
    public function normalize(object $object, ?NormalizerContextInterface $context = null, string $path = ''): array;
}
