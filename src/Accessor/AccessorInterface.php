<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Accessor;

use Chubbyphp\Serialization\SerializerLogicException;

interface AccessorInterface
{
    /**
     * @throws SerializerLogicException
     *
     * @return mixed
     */
    public function getValue(object $object);
}
