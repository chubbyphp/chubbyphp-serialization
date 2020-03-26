<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Policy;

use Chubbyphp\Serialization\Normalizer\NormalizerContextInterface;

final class AndPolicy implements PolicyInterface
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
            if (false === $policy->isCompliant($context, $object)) {
                return false;
            }
        }

        return true;
    }

    public function isCompliantIncludingPath(object $object, NormalizerContextInterface $context, string $path): bool
    {
        foreach ($this->policies as $policy) {
            if (is_callable([$policy, 'isCompliantIncludingPath'])) {
                if (false === $policy->isCompliantIncludingPath($object, $context, $path)) {
                    return false;
                }

                continue;
            }

            @trigger_error('Use "isCompliantIncludingPath()" instead of "isCompliant()"', E_USER_DEPRECATED);

            if (false === $policy->isCompliant($context,$object)) {
                return false;
            }
        }

        return true;
    }
}
