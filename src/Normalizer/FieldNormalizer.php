<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Normalizer;

use Chubbyphp\Serialization\Accessor\AccessorInterface;

final class FieldNormalizer implements FieldNormalizerInterface
{
    private AccessorInterface $accessor;

    public function __construct(AccessorInterface $accessor)
    {
        $this->accessor = $accessor;
    }

    /**
     * @return mixed
     */
    public function normalizeField(
        string $path,
        object $object,
        NormalizerContextInterface $context,
        ?NormalizerInterface $normalizer = null
    ) {
        return $this->accessor->getValue($object);
    }
}
