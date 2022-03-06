<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Policy;

use Chubbyphp\Serialization\Normalizer\NormalizerContextInterface;

final class NotPolicy implements PolicyInterface
{
    public function __construct(private PolicyInterface $policy)
    {
    }

    public function isCompliant(string $path, object $object, NormalizerContextInterface $context): bool
    {
        return !$this->policy->isCompliant($path, $object, $context);
    }
}
