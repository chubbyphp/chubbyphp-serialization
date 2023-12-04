<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Policy;

use Chubbyphp\Serialization\Normalizer\NormalizerContextInterface;

final class OrPolicy implements PolicyInterface
{
    /**
     * @param array<int, PolicyInterface> $policies
     */
    public function __construct(private array $policies) {}

    public function isCompliant(string $path, object $object, NormalizerContextInterface $context): bool
    {
        foreach ($this->policies as $policy) {
            if ($policy->isCompliant($path, $object, $context)) {
                return true;
            }
        }

        return false;
    }
}
