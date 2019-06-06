<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Normalizer;

use Chubbyphp\Serialization\SerializerLogicException;
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
     * @param object                     $object
     * @param NormalizerContextInterface $context
     *
     * @return array|null
     *
     * @throws SerializerLogicException
     */
    public function normalizeLink(string $path, $object, NormalizerContextInterface $context)
    {
        $callback = $this->callback;

        $link = $callback($path, $object, $context);

        if (null === $link) {
            return null;
        }

        if (!$link instanceof LinkInterface) {
            $type = is_object($link) ? get_class($link) : gettype($link);

            throw SerializerLogicException::createInvalidLinkTypeReturned($path, $type);
        }

        return (new LinkNormalizer($link))->normalizeLink($path, $object, $context);
    }
}
