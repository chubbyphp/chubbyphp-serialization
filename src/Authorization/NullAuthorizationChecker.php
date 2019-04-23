<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Authorization;

final class NullAuthorizationChecker implements AuthorizationCheckerInterface
{
    /**
     * @param object|string|null $role
     * @param object             $object
     * @param object|string|null $permission
     *
     * @return bool
     */
    public function isAllowed($role, $object, $permission): bool
    {
        return true;
    }
}
