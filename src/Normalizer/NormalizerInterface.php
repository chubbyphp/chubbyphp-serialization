<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Normalizer;

use Chubbyphp\Serialization\SerializerLogicException;

interface NormalizerInterface
{
    /**
     * @return array<string, null|array|bool|float|int|string>
     *
     * @throws SerializerLogicException
     */
    public function normalize(object $object, ?NormalizerContextInterface $context = null, string $path = ''): array;
}
