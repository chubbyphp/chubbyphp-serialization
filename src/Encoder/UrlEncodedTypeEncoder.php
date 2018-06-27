<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Encoder;

final class UrlEncodedTypeEncoder implements TypeEncoderInterface
{
    /**
     * @return string
     */
    public function getContentType(): string
    {
        return 'application/x-www-form-urlencoded';
    }

    /**
     * @param array $data
     *
     * @return string
     */
    public function encode(array $data): string
    {
        return $this->buildQuery($data);
    }

    /**
     * @param array  $data
     * @param string $path
     *
     * @return string
     */
    private function buildQuery(array $data, string $path = ''): string
    {
        if ([] === $data) {
            return '';
        }

        $query = '';
        foreach ($data as $key => $value) {
            if (null === $value) {
                continue;
            }

            $subPath = '' !== $path ? $path.'['.$key.']' : (string) $key;
            if (is_array($value)) {
                $query .= $this->buildQuery($value, $subPath);
            } else {
                $query .= $subPath.'='.urlencode($this->getValueAsString($value));
            }
            $query .= '&';
        }

        $query = substr($query, 0, -strlen('&'));

        return $query;
    }

    /**
     * @param bool|int|float|string $value
     *
     * @return string
     *
     * @throws \InvalidArgumentException
     */
    private function getValueAsString($value): string
    {
        if (is_string($value)) {
            return $value;
        }

        if (is_bool($value)) {
            return $value ? 'true' : 'false';
        }

        if (is_float($value)) {
            $value = (string) $value;
            if (false === strpos($value, '.')) {
                $value .= '.0';
            }

            return $value;
        }

        if (is_int($value)) {
            return (string) $value;
        }

        throw new \InvalidArgumentException(sprintf('Unsupported data type: %s', gettype($value)));
    }
}
