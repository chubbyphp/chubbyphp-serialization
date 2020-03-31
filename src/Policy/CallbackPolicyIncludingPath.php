<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Policy;

use Chubbyphp\Serialization\Normalizer\NormalizerContextInterface;

final class CallbackPolicyIncludingPath implements PolicyInterface
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
     * @deprecated
     */
    public function isCompliant(NormalizerContextInterface $context, object $object): bool
    {
        @trigger_error('Use "isCompliantIncludingPath()" instead of "isCompliant()"', E_USER_DEPRECATED);

        return ($this->callback)($context, $object);
    }

    public function isCompliantIncludingPath(string $path, object $object, NormalizerContextInterface $context): bool
    {
        return ($this->callback)($path, $object, $context);
    }
}
