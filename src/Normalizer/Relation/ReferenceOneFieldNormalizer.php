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
    /**
     * @var AccessorInterface
     */
    private $identifierAccessor;

    /**
     * @var AccessorInterface
     */
    private $accessor;

    /**
     * @param AccessorInterface $identifierAccessor
     * @param AccessorInterface $accessor
     */
    public function __construct(AccessorInterface $identifierAccessor, AccessorInterface $accessor)
    {
        $this->identifierAccessor = $identifierAccessor;
        $this->accessor = $accessor;
    }

    /**
     * @param string                     $path
     * @param object                     $object
     * @param NormalizerContextInterface $context
     * @param NormalizerInterface|null   $normalizer
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
    ) {
        if (null === $relatedObject = $this->accessor->getValue($object)) {
            return null;
        }

        return $this->identifierAccessor->getValue($relatedObject);
    }
}
