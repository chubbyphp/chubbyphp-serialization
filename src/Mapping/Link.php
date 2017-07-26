<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Mapping;

final class Link implements LinkInterface
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $method;

    /**
     * @var string
     */
    private $href;

    /**
     * @var array
     */
    private $options;

    /**
     * @param string $name
     * @param string $method
     * @param string $href
     * @param array $options
     */
    public function __construct(string $name, string $method, string $href, array $options = [])
    {
        $this->name = $name;
        $this->method = $method;
        $this->href = $href;
        $this->options = $options;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @return string
     */
    public function getHref(): string
    {
        return $this->href;
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return $this->options;
    }
}
