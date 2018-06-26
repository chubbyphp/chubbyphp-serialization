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
            $subPath = '' !== $path ? $path.'['.$key.']' : (string) $key;
            if (is_array($value)) {
                $query .= $this->buildQuery($value, $subPath);
            } else {
                $query .= $subPath.'='.urlencode($this->convertValueToString($value));
            }
            $query .= '&';
        }

        $query = substr($query, 0, -strlen('&'));

        return $query;
    }

    /**
     * @param null|bool|int|string $value
     *
     * @return string
     */
    private function convertValueToString($value): string
    {
        if (true === $value) {
            return 'true';
        }

        if (false === $value) {
            return 'false';
        }

        return (string) $value;
    }
}
