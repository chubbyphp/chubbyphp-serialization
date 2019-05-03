<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Normalizer;

final class RootAwareFieldNormalizerDecorator implements FieldNormalizerInterface
{
    /**
     * @var string
     */
    const ATTRIBUTE_ROOT = 'root';

    /**
     * @var FieldNormalizerInterface
     */
    private $fieldNormalizer;

    /**
     * @param FieldNormalizerInterface $fieldNormalizer
     */
    public function __construct(FieldNormalizerInterface $fieldNormalizer)
    {
        $this->fieldNormalizer = $fieldNormalizer;
    }

    /**
     * @param string                     $path
     * @param object|mixed               $object
     * @param NormalizerContextInterface $context
     * @param NormalizerInterface|null   $normalizer
     *
     * @return mixed|void
     */
    public function normalizeField(
        string $path,
        $object,
        NormalizerContextInterface $context,
        NormalizerInterface $normalizer = null
    ) {
        if (null === $context->getAttribute(self::ATTRIBUTE_ROOT)) {
            $context = $context->withAttribute(self::ATTRIBUTE_ROOT, $object);
        }

        return $this->fieldNormalizer->normalizeField($path, $object, $context, $normalizer);
    }
}
