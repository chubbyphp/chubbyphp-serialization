<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization;

use Chubbyphp\Serialization\Normalizer\NormalizerContextInterface;

interface SerializerInterface
{
    /**
     * @param object                     $object
     * @param string                     $contentType
     * @param NormalizerContextInterface $context
     *
     * @return string
     */
    public function serialize($object, string $contentType, NormalizerContextInterface $context = null): string;
}
