<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Encoder;

final class UrlEncodedTypeEncoder implements TypeEncoderInterface
{
    public function getContentType(): string
    {
        return 'application/x-www-form-urlencoded';
    }

    /**
     * @param array<mixed> $data
     */
    public function encode(array $data): string
    {
        return $this->buildQuery($data);
    }

    /**
     * @param array<mixed> $data
     */
    private function buildQuery(array $data, string $path = ''): string
    {
        $query = '';
        foreach ($data as $key => $value) {
            if (null === $value || $value === []) {
                continue;
            }

            $subPath = '' !== $path ? $path.'['.$key.']' : (string) $key;

            if (is_array($value)) {
                $queryPart = $this->buildQuery($value, $subPath);

                $query .= '' !== $queryPart ? $queryPart.'&' : '';
            } else {
                $query .= $subPath.'='.urlencode($this->getValueAsString($value)).'&';
            }
        }

        return substr($query, 0, -1);
    }

    /**
     * @param bool|int|float|string $value
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
