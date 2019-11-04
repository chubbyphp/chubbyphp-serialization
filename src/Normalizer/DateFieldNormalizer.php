<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Normalizer;

/**
 * @deprecated use Chubbyphp\Serialization\Normalizer\DateTimeFieldNormalizer
 */
final class DateFieldNormalizer implements FieldNormalizerInterface
{
    /**
     * @var FieldNormalizerInterface
     */
    private $fieldNormalizer;

    public function __construct(FieldNormalizerInterface $fieldNormalizer, string $format = 'c')
    {
        $this->fieldNormalizer = new DateTimeFieldNormalizer($fieldNormalizer, $format);
    }

    /**
     * @param object $object
     *
     * @return mixed
     */
    public function normalizeField(
        string $path,
        $object,
        NormalizerContextInterface $context,
        NormalizerInterface $normalizer = null
    ) {
        @trigger_error(sprintf('Use %s instead', DateTimeFieldNormalizer::class), E_USER_DEPRECATED);

        return $this->fieldNormalizer->normalizeField($path, $object, $context, $normalizer);
    }
}
