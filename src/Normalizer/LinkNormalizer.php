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

    /**
     * @param LinkInterface $link
     */
    public function __construct(LinkInterface $link)
    {
        $this->link = $link;
    }

    /**
     * @param string                     $path
     * @param object|mixed               $object
     * @param NormalizerContextInterface $context
     *
     * @throws SerializerLogicException
     *
     * @return array|null
     */
    public function normalizeLink(string $path, $object, NormalizerContextInterface $context)
    {
        return [
            'href' => $this->link->getHref(),
            'templated' => $this->link->isTemplated(),
            'rel' => $this->link->getRels(),
            'attributes' => $this->link->getAttributes(),
        ];
    }
}
