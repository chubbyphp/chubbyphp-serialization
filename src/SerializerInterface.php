<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization;

use Psr\Http\Message\ServerRequestInterface as Request;

interface SerializerInterface
{
    /**
     * @param Request $request
     * @param object  $object
     * @param string  $path
     *
     * @return array
     *
     * @throws NotObjectException
     */
    public function serialize(Request $request, $object, string $path = ''): array;
}
