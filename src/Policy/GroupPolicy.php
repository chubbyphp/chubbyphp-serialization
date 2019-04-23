<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Policy;

use Chubbyphp\Serialization\Normalizer\NormalizerContextInterface;

final class GroupPolicy implements PolicyInterface
{
    /**
     * @var string[]
     */
    private $groups;

    /**
     * @param array $groups
     */
    public function __construct(array $groups)
    {
        $this->groups = $groups;
    }

    /**
     * @param NormalizerContextInterface $context
     * @param object                     $object
     *
     * @return bool
     */
    public function isCompliant(NormalizerContextInterface $context, $object): bool
    {
        if ([] === $groups = $context->getAttribute('groups', [])) {
            return true;
        }

        foreach ($this->groups as $group) {
            if (in_array($group, $groups, true)) {
                return true;
            }
        }

        return false;
    }
}
