<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization;

interface SerializerInterface
{
    /**
     * @param object $object
     * @param string $path
     * @return array
     */
    public function serialize($object, string $path = ''): array;
}
