<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Link;

final class Link implements LinkInterface
{
    /**
     * @var string
     */
    private $href;

    /**
     * @var string
     */
    private $method;

    /**
     * @var array
     */
    private $attributes;

    /**
     * @param string     $href
     * @param string     $method
     * @param array|null $attributes
     */
    public function __construct($href, $method, array $attributes = null)
    {
        $this->href = $href;
        $this->method = $method;
        $this->attributes = $attributes;
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        $data = [
            'href' => $this->href,
            'method' => $this->method,
        ];

        if (null !== $this->attributes) {
            $data['attributes'] = $this->attributes;
        }

        return $data;
    }
}
