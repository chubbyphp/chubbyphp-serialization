<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Normalizer;

final class CallbackFieldNormalizer implements FieldNormalizerInterface
{
    /**
     * @var callable
     */
    private $callback;

    /**
     * @param callable $callback
     */
    public function __construct(callable $callback)
    {
        $this->callback = $callback;
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
        $callback = $this->callback;

        return $callback($path, $object, $context, $normalizer);
    }
}
