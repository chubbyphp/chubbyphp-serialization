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
     * @var string
     */
    const GROUP_DEFAULT = 'default';

    /**
     * @var string[]
     */
    private $groups;

    /**
     * @param array $groups
     */
    public function __construct(array $groups = [self::GROUP_DEFAULT])
    {
        $this->groups = $groups;
    }

    /**
     * @param NormalizerContextInterface $context
     * @param object|mixed               $object
     *
     * @return bool
     */
    public function isCompliant(NormalizerContextInterface $context, $object): bool
    {
        if ([] === $this->groups) {
            return true;
        }

        $contextGroups = $context->getAttribute(self::ATTRIBUTE_GROUPS, [self::GROUP_DEFAULT]);

        foreach ($this->groups as $group) {
            if (in_array($group, $contextGroups, true)) {
                return true;
            }
        }

        return false;
    }
}
