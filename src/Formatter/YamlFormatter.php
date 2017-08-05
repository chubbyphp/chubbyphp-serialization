<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Formatter;

use Symfony\Component\Yaml\Yaml;

final class YamlFormatter implements FormatterInterface
{
    /**
     * @var int
     */
    private $inline;

    /**
     * @var int
     */
    private $indent;

    /**
     * @var int
     */
    private $flags;

    /**
     * YamlFormatter constructor.
     *
     * @param int $inline
     * @param int $indent
     * @param int $flags
     */
    public function __construct(int $inline = 10, int $indent = 4, int $flags = 0)
    {
        $this->inline = $inline;
        $this->indent = $indent;
        $this->flags = $flags;
    }

    /**
     * @param array $data
     *
     * @return string
     */
    public function format(array $data): string
    {
        return trim(Yaml::dump($data, $this->inline, $this->indent, $this->flags));
    }
}
