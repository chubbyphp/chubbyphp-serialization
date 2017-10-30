<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Normalizer\Relation;

use Chubbyphp\Serialization\Accessor\AccessorInterface;
use Chubbyphp\Serialization\Normalizer\NormalizerContextInterface;
use Chubbyphp\Serialization\Normalizer\NormalizerInterface;
use Chubbyphp\Serialization\Normalizer\FieldNormalizerInterface;
use Chubbyphp\Serialization\SerializerLogicException;

final class ReferenceManyFieldNormalizer implements FieldNormalizerInterface
{
    /**
     * @var AccessorInterface
     */
    private $childAccessor;

    /**
     * @var AccessorInterface
     */
    private $accessor;

    /**
     * @param AccessorInterface $childAccessor
     * @param AccessorInterface $accessor
     */
    public function __construct(AccessorInterface $childAccessor, AccessorInterface $accessor)
    {
        $this->childAccessor = $childAccessor;
        $this->accessor = $accessor;
    }

    /**
     * @param string                     $path
     * @param object                     $object
     * @param NormalizerContextInterface $context
     * @param NormalizerInterface|null   $normalizer
     *
     * @return mixed
     *
     * @throws SerializerLogicException
     */
    public function normalizeField(
        string $path,
        $object,
        NormalizerContextInterface $context,
        NormalizerInterface $normalizer = null
    ) {
        if (null === $childObjects = $this->accessor->getValue($object)) {
            return null;
        }

        $values = [];
        foreach ($childObjects as $i => $childObject) {
            $values[$i] = $this->childAccessor->getValue($childObject);
        }

        return $values;
    }
}
