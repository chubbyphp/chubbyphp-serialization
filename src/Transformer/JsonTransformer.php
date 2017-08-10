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
    private $depth;

    /**
     * JsonTransformer constructor.
     *
     * @param int $options
     * @param int $depth
     */
    public function __construct(int $options = JSON_UNESCAPED_SLASHES, int $depth = 512)
    {
        $this->options = $options;
        $this->depth = $depth;
    }

    /**
     * @param array $data
     *
     * @return string
     */
    public function transform(array $data): string
    {
        return json_encode($data, $this->options, $this->depth);
    }
}
