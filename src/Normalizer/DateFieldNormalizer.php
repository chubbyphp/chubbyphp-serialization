<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Normalizer;

final class DateFieldNormalizer implements FieldNormalizerInterface
{
    /**
     * @var FieldNormalizerInterface
     */
    private $fieldNormalizer;

    /**
     * @var string
     */
    private $format;

    /**
     * @param FieldNormalizerInterface $fieldNormalizer
     * @param string                   $format
     */
    public function __construct(FieldNormalizerInterface $fieldNormalizer, string $format = 'c')
    {
        $this->fieldNormalizer = $fieldNormalizer;
        $this->format = $format;
    }

    /**
     * @param string                     $path
     * @param object                     $object
     * @param mixed                      $value
     * @param NormalizerContextInterface $context
     * @param NormalizerInterface|null   $normalizer
     *
     * @return mixed
     */
    public function normalizeField(
        string $path,
        $object,
        NormalizerContextInterface $context,
        NormalizerInterface $normalizer = null
    ) {
        $value = $this->fieldNormalizer->normalizeField($path, $object, $context, $normalizer);

        if (is_string($value)) {
            try {
                $value = new \DateTime($value);
            } catch (\Exception $exception) {
            }
        }

        if (!$value instanceof \DateTimeInterface) {
            return $value;
        }

        return $value->format($this->format);
    }
}
