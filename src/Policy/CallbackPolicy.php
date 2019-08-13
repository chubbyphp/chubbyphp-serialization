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

    /**
     * @param callable $callback
     */
    public function __construct(callable $callback)
    {
        $this->callback = $callback;
    }

    /**
     * @param NormalizerContextInterface $context
     * @param object|mixed               $object
     *
     * @return bool
     */
    public function isCompliant(NormalizerContextInterface $context, $object): bool
    {
        return ($this->callback)($context, $object);
    }
}
