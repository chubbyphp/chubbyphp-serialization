<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Normalizer;

final class CallbackFieldNormalizer implements FieldNormalizerInterface
{
    /**
     * @var callable
     */
    private $callback;

    public function __construct(callable $callback)
    {
        $this->callback = $callback;
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
        $callback = $this->callback;

        return $callback($path, $object, $context, $normalizer);
    }
}
