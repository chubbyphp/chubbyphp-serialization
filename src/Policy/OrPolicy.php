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
        @trigger_error('Use "isCompliantIncludingPath()" instead of "isCompliant()"', E_USER_DEPRECATED);

        foreach ($this->policies as $policy) {
            if ($policy->isCompliant($context, $object)) {
                return true;
            }
        }

        return false;
    }

    public function isCompliantIncludingPath(string $path, object $object, NormalizerContextInterface $context): bool
    {
        foreach ($this->policies as $policy) {
            if (is_callable([$policy, 'isCompliantIncludingPath'])) {
                if ($policy->isCompliantIncludingPath($path, $object, $context)) {
                    return true;
                }

                continue;
            }

            @trigger_error('Use "isCompliantIncludingPath()" instead of "isCompliant()"', E_USER_DEPRECATED);

            if ($policy->isCompliant($context, $object)) {
                return true;
            }
        }

        return false;
    }
}
