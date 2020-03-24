<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Normalizer;

use Chubbyphp\Serialization\SerializerLogicException;
use Psr\Link\LinkInterface;

final class LinkNormalizer implements LinkNormalizerInterface
{
    /**
     * @var LinkInterface
     */
    private $link;

    public function __construct(LinkInterface $link)
    {
        $this->link = $link;
    }

    /**
     * @param object|mixed $object
     *
     * @throws SerializerLogicException
     *
     * @return array<mixed>|null
     */
    public function normalizeLink(string $path, object $object, NormalizerContextInterface $context)
    {
        return [
            'href' => $this->link->getHref(),
            'templated' => $this->link->isTemplated(),
            'rel' => $this->link->getRels(),
            'attributes' => $this->link->getAttributes(),
        ];
    }
}
