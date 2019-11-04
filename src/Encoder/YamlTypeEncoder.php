<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Encoder;

use Symfony\Component\Yaml\Yaml;

final class YamlTypeEncoder implements TypeEncoderInterface
{
    public function getContentType(): string
    {
        return 'application/x-yaml';
    }

    public function encode(array $data): string
    {
        return trim(Yaml::dump($data, 10, 4));
    }
}
