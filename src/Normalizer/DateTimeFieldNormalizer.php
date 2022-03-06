<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Normalizer;

use Chubbyphp\Serialization\Accessor\AccessorInterface;

final class DateTimeFieldNormalizer implements FieldNormalizerInterface
{
    public function __construct(private AccessorInterface $accessor, private string $format = 'c')
    {
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
        $value = $this->accessor->getValue($object);

        if (\is_string($value)) {
            try {
                $value = new \DateTimeImmutable($value);
            } catch (\Exception) {
            }
        }

        if (!$value instanceof \DateTimeInterface) {
            return $value;
        }

        return $value->format($this->format);
    }
}
