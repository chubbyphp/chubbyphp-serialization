<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Normalizer;

use Chubbyphp\Serialization\Accessor\AccessorInterface;

final class DateTimeFieldNormalizer implements FieldNormalizerInterface
{
    /**
     * @var AccessorInterface
     */
    private $accessor;

    /**
     * @var string
     */
    private $format;

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
