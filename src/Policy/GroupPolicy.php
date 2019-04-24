<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Policy;

use Chubbyphp\Serialization\Normalizer\NormalizerContextInterface;

final class GroupPolicy implements PolicyInterface
{
    /**
     * @var string
     */
    const ATTRIBUTE_GROUPS = 'groups';

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
        if ([] === $groups = $context->getAttribute(self::ATTRIBUTE_GROUPS, [])) {
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
