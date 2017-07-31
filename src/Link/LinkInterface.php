<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Link;

interface LinkInterface extends \JsonSerializable
{
    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';
    const METHOD_PUT = 'PUT';
    const METHOD_DELETE = 'DELETE';
}
