<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Policy;

use Chubbyphp\Serialization\Normalizer\NormalizerContextInterface;

final class AndPolicy implements PolicyInterface
{
    /**
     * @var array|PolicyInterface[]
     */
    private $policies;

    /**
     * @param array|PolicyInterface[] $policies
     */
    public function __construct(array $policies)
    {
        $this->policies = $policies;
    }

    /**
     * @param object|mixed $object
     */
    public function isCompliant(NormalizerContextInterface $context, $object): bool
    {
        foreach ($this->policies as $policy) {
            if (false === $policy->isCompliant($context, $object)) {
                return false;
            }
        }

        return true;
    }
}
