<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Normalizer;

use Chubbyphp\Serialization\Accessor\AccessorInterface;

final class DateTimeFieldNormalizer implements FieldNormalizerInterface
{
    private AccessorInterface $accessor;

    private string $format;

    public function __construct(AccessorInterface $accessor, string $format = 'c')
    {
        $this->accessor = $accessor;
        $this->format = $format;
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
            } catch (\Exception $exception) {
            }
        }

        if (!$value instanceof \DateTimeInterface) {
            return $value;
        }

        return $value->format($this->format);
    }
}
