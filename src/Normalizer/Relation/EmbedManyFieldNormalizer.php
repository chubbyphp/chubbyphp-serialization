<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Normalizer\Relation;

use Chubbyphp\Serialization\Accessor\AccessorInterface;
use Chubbyphp\Serialization\Normalizer\NormalizerContextInterface;
use Chubbyphp\Serialization\Normalizer\NormalizerInterface;
use Chubbyphp\Serialization\Normalizer\FieldNormalizerInterface;
use Chubbyphp\Serialization\SerializerLogicException;
use Doctrine\Common\Persistence\Proxy;

final class EmbedManyFieldNormalizer implements FieldNormalizerInterface
{
    /**
     * @var AccessorInterface
     */
    private $accessor;

    /**
     * @param AccessorInterface $accessor
     */
    public function __construct(AccessorInterface $accessor)
    {
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
        if (null === $normalizer) {
            throw SerializerLogicException::createMissingNormalizer($path);
        }

        if (null === $relatedObjects = $this->accessor->getValue($object)) {
            return null;
        }

        $values = [];
        foreach ($relatedObjects as $i => $relatedObject) {
            $this->resolveProxy($relatedObject);

            $subPath = $path.'['.$i.']';
            $values[$i] = $normalizer->normalize($relatedObject, $context, $subPath);
        }

        return $values;
    }

    private function resolveProxy($relatedObject)
    {
        if (null !== $relatedObject && interface_exists('Doctrine\Common\Persistence\Proxy')
            && $relatedObject instanceof Proxy && !$relatedObject->__isInitialized()
        ) {
            $relatedObject->__load();
        }
    }
}
