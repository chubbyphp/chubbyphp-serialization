<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Policy;

use Chubbyphp\Serialization\Normalizer\NormalizerContextInterface;

final class OrPolicy implements PolicyInterface
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
     * @param NormalizerContextInterface $context
     * @param object|mixed               $object
     *
     * @return bool
     */
    public function isCompliant(NormalizerContextInterface $context, $object): bool
    {
        foreach ($this->policies as $policy) {
            if ($policy->isCompliant($context, $object)) {
                return true;
            }
        }

        return false;
    }
}
