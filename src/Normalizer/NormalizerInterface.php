<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Normalizer;

use Chubbyphp\Serialization\SerializerLogicException;

interface NormalizerInterface
{
    /**
     * @param object                          $object
     * @param NormalizerContextInterface|null $context
     * @param string                          $path
     *
     * @throws SerializerLogicException
     *
     * @return array
     */
    public function normalize($object, NormalizerContextInterface $context = null, string $path = ''): array;
}
