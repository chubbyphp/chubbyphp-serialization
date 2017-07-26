<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Mapping;

interface LinkInterface
{
    const METHOD_OPTIONS = 'options';
    const METHOD_GET = 'get';
    const METHOD_HEAD = 'head';
    const METHOD_POST = 'post';
    const METHOD_PUT = 'put';
    const METHOD_PATCH = 'patch';
    const METHOD_DELETE = 'delete';

    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return string
     */
    public function getMethod(): string;

    /**
     * @return string
     */
    public function getHref(): string;

    /**
     * @return array
     */
    public function getOptions(): array;
}
