<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Normalizer;

use Chubbyphp\Serialization\SerializerLogicException;
use Psr\Link\LinkInterface;

final class LinkNormalizer implements LinkNormalizerInterface
{
    public function __construct(private LinkInterface $link)
    {
    }

    /**
     * @throws SerializerLogicException
     *
     * @return array<string, mixed>
     */
    public function normalizeLink(string $path, object $object, NormalizerContextInterface $context): array
    {
        return [
            'href' => $this->link->getHref(),
            'templated' => $this->link->isTemplated(),
            'rel' => $this->link->getRels(),
            'attributes' => $this->link->getAttributes(),
        ];
    }
}
