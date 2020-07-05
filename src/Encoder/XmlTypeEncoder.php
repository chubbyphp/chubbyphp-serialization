<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Encoder;

final class XmlTypeEncoder implements TypeEncoderInterface
{
    /**
     * @var JsonxTypeEncoder
     */
    private $jsonxTypeEncoder;

    public function __construct(bool $prettyPrint = false)
    {
        $this->jsonxTypeEncoder = new JsonxTypeEncoder($prettyPrint);
    }

    public function getContentType(): string
    {
        return 'application/xml';
    }

    /**
     * @param array<string, array|string|float|int|bool|null> $data
     */
    public function encode(array $data): string
    {
        return $this->jsonxTypeEncoder->encode($data);
    }
}
