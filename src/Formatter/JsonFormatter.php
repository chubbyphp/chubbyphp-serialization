<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Formatter;

final class JsonFormatter implements FormatterInterface
{
    /**
     * @var int
     */
    private $options;

    /**
     * @var int
     */
    private $level;

    /**
     * JsonFormatter constructor.
     *
     * @param int $options
     * @param int $level
     */
    public function __construct(int $options = JSON_UNESCAPED_SLASHES, int $level = 512)
    {
        $this->options = $options;
        $this->level = $level;
    }

    /**
     * @param array $data
     *
     * @return string
     */
    public function format(array $data): string
    {
        return json_encode($data, $this->options, $this->level);
    }
}
