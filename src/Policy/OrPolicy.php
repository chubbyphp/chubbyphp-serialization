<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Policy;

use Chubbyphp\Serialization\Normalizer\NormalizerContextInterface;

final class OrPolicy implements PolicyInterface
{
    /**
     * @var array<int, PolicyInterface>
     */
    private $policies;

    /**
     * @param array<int, PolicyInterface> $policies
     */
    public function __construct(array $policies)
    {
        $this->policies = $policies;
    }

    /**
     * @deprecated
     */
    public function isCompliant(NormalizerContextInterface $context, object $object): bool
    {
        foreach ($this->policies as $policy) {
            if ($policy->isCompliant($context, $object)) {
                return true;
            }
        }

        return false;
    }

    public function isCompliantIncludingPath(object $object, NormalizerContextInterface $context, string $path): bool
    {
        foreach ($this->policies as $policy) {
            if (is_callable([$policy, 'isCompliantIncludingPath'])) {
                if ($policy->isCompliantIncludingPath($object, $context, $path)) {
                    return true;
                }

                continue;
            }

            if ($policy->isCompliant($context, $object)) {
                return true;
            }
        }

        return false;
    }
}
