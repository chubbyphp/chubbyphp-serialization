<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Normalizer;

use Psr\Link\LinkInterface;

final class CallbackLinkNormalizer implements LinkNormalizerInterface
{
    /**
     * @var callable
     */
    private $callback;

    /**
     * @param callable $callback
     */
    public function __construct(callable $callback)
    {
        $this->callback = $callback;
    }

    /**
     * @param string                     $path
     * @param object              $object
     * @param NormalizerContextInterface $context
     *
     * @return array|null
     */
    public function normalizeLink(string $path, $object, NormalizerContextInterface $context)
    {
        $callback = $this->callback;

        $link = $callback($path, $object, $context);

        if (null === $link) {
            return null;
        }

        if (!$link instanceof LinkInterface) {
            // todo: exception
        }

        return [
            'href' => $link->getHref(),
            'templated' => $link->isTemplated(),
            'rel' => $link->getRels(),
            'attributes' => $link->getAttributes()
        ];
    }
}
