<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Normalizer\Relation;

use Chubbyphp\Serialization\Accessor\AccessorInterface;
use Chubbyphp\Serialization\Normalizer\FieldNormalizerInterface;
use Chubbyphp\Serialization\Normalizer\NormalizerContextInterface;
use Chubbyphp\Serialization\Normalizer\NormalizerInterface;
use Chubbyphp\Serialization\SerializerLogicException;

final class ReferenceOneFieldNormalizer implements FieldNormalizerInterface
{
    public function __construct(private AccessorInterface $identifierAccessor, private AccessorInterface $accessor) {}

    /**
     * @return mixed
     *
     * @throws SerializerLogicException
     */
    public function normalizeField(
        string $path,
        object $object,
        NormalizerContextInterface $context,
        ?NormalizerInterface $normalizer = null
    ) {
        if (null === $relatedObject = $this->accessor->getValue($object)) {
            return null;
        }

        return $this->identifierAccessor->getValue($relatedObject);
    }
}
