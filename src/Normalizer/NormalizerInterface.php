<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Normalizer;

use Chubbyphp\Serialization\SerializerLogicException;

interface NormalizerInterface
{
    /**
     * @param object $object
     *
     * @throws SerializerLogicException
     *
     * @return array<mixed>
     */
    public function normalize($object, ?NormalizerContextInterface $context = null, string $path = ''): array;
}
