<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Policy;

use Chubbyphp\Serialization\Normalizer\NormalizerContextInterface;

final class CallbackPolicy implements PolicyInterface
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
     * @param object|mixed $object
     */
    public function isCompliant(NormalizerContextInterface $context, $object): bool
    {
        return ($this->callback)($context, $object);
    }
}
