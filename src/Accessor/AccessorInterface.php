<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Accessor;

use Chubbyphp\Serialization\SerializerLogicException;

interface AccessorInterface
{
    /**
     * @return mixed
     *
     * @throws SerializerLogicException
     */
    public function getValue(object $object);
}
