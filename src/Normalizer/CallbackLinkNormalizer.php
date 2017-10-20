<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Normalizer;

use Psr\Link\LinkInterface;
use Psr\Http\Message\ServerRequestInterface as Request;

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
     * @param Request                    $request
     * @param object                     $object
     * @param NormalizerContextInterface $context
     *
     * @return array|null
     */
    public function normalizeLink(string $path, Request $request, $object, NormalizerContextInterface $context)
    {
        $callback = $this->callback;

        $link = $callback($path, $request, $object, $context);

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
            'attributes' => $link->getAttributes(),
        ];
    }
}
