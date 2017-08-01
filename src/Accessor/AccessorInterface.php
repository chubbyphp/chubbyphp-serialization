<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Accessor;

interface AccessorInterface
{
    /**
     * @param object $object
     *
     * @return mixed
     */
    public function getValue($object);
}
