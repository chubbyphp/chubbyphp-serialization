<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Policy;

use Chubbyphp\Serialization\Normalizer\NormalizerContextInterface;

/**
 * @deprecated use CallbackPolicyIncludingPath instead
 */
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

    public function isCompliant(NormalizerContextInterface $context, object $object): bool
    {
        @trigger_error('Use "CallbackPolicyIncludingPath" instead of "CallbackPolicy"', E_USER_DEPRECATED);

        return ($this->callback)($context, $object);
    }
}
