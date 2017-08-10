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
     * @param string $numericPrefix
     * @param string $argSeperator
     */
    public function __construct(string $numericPrefix = '', string $argSeperator = '&')
    {
        $this->numericPrefix = $numericPrefix;
        $this->argSeperator = $argSeperator;
    }

    /**
     * @param array $data
     *
     * @return string
     */
    public function transform(array $data): string
    {
        $query = $this->buildQuery($data);

        return $query;
    }

    /**
     * @param array  $data
     * @param string $path
     *
     * @return string
     */
    private function buildQuery(array $data, string $path = ''): string
    {
        $query = '';
        foreach ($data as $key => $value) {
            $subPathKey = !is_int($key) ? $key : $this->numericPrefix.$key;
            $subPath = '' !== $path ? $path.'['.$subPathKey.']' : $subPathKey;

            if (is_array($value)) {
                $query .= $this->buildQuery($value, $subPath);
            } else {
                $query .= $subPath.'='.urlencode((string) $value);
            }

            $query .= $this->argSeperator;
        }

        $query = substr($query, 0, -strlen($this->argSeperator));

        return $query;
    }
}
