<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Normalizer;

use Chubbyphp\Serialization\SerializerLogicException;

interface FieldNormalizerInterface
{
    /**
     * @param object $object
     *
     * @throws SerializerLogicException
     *
     * @return mixed
     */
    public function normalizeField(
        string $path,
        $object,
        NormalizerContextInterface $context,
        NormalizerInterface $normalizer = null
    );
}
