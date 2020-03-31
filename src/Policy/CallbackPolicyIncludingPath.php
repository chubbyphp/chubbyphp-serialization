<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Policy;

use Chubbyphp\Serialization\Normalizer\NormalizerContextInterface;
use Chubbyphp\Serialization\SerializerLogicException;

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
     *
     * @throws SerializerLogicException
     */
    public function isCompliant(NormalizerContextInterface $context, object $object): bool
    {
        throw SerializerLogicException::createMissingMethod(__CLASS__, ['isCompliant']);
    }

    public function isCompliantIncludingPath(string $path, object $object, NormalizerContextInterface $context): bool
    {
        return ($this->callback)($path, $object, $context);
    }
}
