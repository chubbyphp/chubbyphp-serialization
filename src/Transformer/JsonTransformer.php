<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Transformer;

final class JsonTransformer implements TransformerInterface
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
     * JsonTransformer constructor.
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
    public function transform(array $data): string
    {
        return json_encode($data, $this->options, $this->level);
    }
}
