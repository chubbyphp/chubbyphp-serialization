<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Normalizer;

use Chubbyphp\Serialization\Accessor\AccessorInterface;

final class DateTimeFieldNormalizer implements FieldNormalizerInterface
{
    /**
     * @var FieldNormalizerInterface
     */
    private $fieldNormalizer;

    /**
     * @var AccessorInterface
     */
    private $accessor;

    /**
     * @var string
     */
    private $format;

    /**
     * @param AccessorInterface|FieldNormalizerInterface $fieldNormalizer
     * @param string                   $format
     */
    public function __construct($accessor, string $format = 'c')
    {

        $this->format = $format;

        if ($accessor instanceof AccessorInterface) {
            $this->accessor = $accessor;

            return;
        }

        if ($accessor instanceof FieldNormalizerInterface) {
            @trigger_error(
                sprintf(
                    'Use "%s" instead of "%s" as __construct argument',
                    AccessorInterface::class,
                    FieldNormalizerInterface::class
                ),
                E_USER_DEPRECATED
            );

            $this->fieldNormalizer = $accessor;

            return;
        }

        throw new \TypeError(
            sprintf(
                '%s::__construct() expects parameter 1 to be %s|%s, %s given',
                self::class,
                AccessorInterface::class,
                FieldNormalizerInterface::class,
                is_object($accessor) ? get_class($accessor) : gettype($accessor)
            )
        );
    }

    /**
     * @param string                     $path
     * @param object                     $object
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
        $value = $this->getValue($path, $object, $context, $normalizer);

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

    /**
     * @param string $path
     * @param $object
     * @param NormalizerContextInterface $context
     * @param NormalizerInterface|null $normalizer
     * @return mixed
     */
    private function getValue(
        string $path,
        $object,
        NormalizerContextInterface $context,
        NormalizerInterface $normalizer = null
    ) {
        if (null !== $this->accessor) {
            return $this->accessor->getValue($object);
        }

        return $this->fieldNormalizer->normalizeField($path, $object, $context, $normalizer);
    }
}
