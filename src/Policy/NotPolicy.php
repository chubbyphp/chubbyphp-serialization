<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Policy;

use Chubbyphp\Serialization\Normalizer\NormalizerContextInterface;

final class NotPolicy implements PolicyInterface
{
    /**
     * @var PolicyInterface
     */
    private $policy;

    /**
     * @param PolicyInterface $policy
     */
    public function __construct(PolicyInterface $policy)
    {
        $this->policy = $policy;
    }

    /**
     * @param NormalizerContextInterface $context
     * @param object|mixed               $object
     *
     * @return bool
     */
    public function isCompliant(NormalizerContextInterface $context, $object): bool
    {
        return !$this->policy->isCompliant($context, $object);
    }
}
