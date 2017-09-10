<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Link;

use Psr\Http\Message\ServerRequestInterface as Request;

interface LinkGeneratorInterface
{
    /**
     * @param Request $request
     * @param string  $routeName
     * @param array   $data
     * @param array   $queryParams
     *
     * @return LinkInterface
     */
    public function generateLink(
        Request $request,
        string $routeName,
        array $data = [],
        array $queryParams = []
    ): LinkInterface;
}
