<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Transformer;

final class UrlEncodedTransformer implements TransformerInterface
{
    /**
     * @var string
     */
    private $numericPrefix;

    /**
     * @var string
     */
    private $argSeperator;

    /**
     * @var int
     */
    private $encType;

    /**
     * @param string $numericPrefix
     * @param string $argSeperator
     * @param int    $encType
     */
    public function __construct(
        string $numericPrefix = '',
        string $argSeperator = '&',
        int $encType = PHP_QUERY_RFC1738
    ) {
        $this->numericPrefix = $numericPrefix;
        $this->argSeperator = $argSeperator;
        $this->encType = $encType;
    }

    /**
     * @param array $data
     *
     * @return string
     */
    public function transform(array $data): string
    {
        return http_build_query($data, $this->numericPrefix, $this->argSeperator, $this->encType);
    }
}
