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

    public function __construct(callable $callback)
    {
        $this->callback = $callback;
    }

    /**
     * @throws SerializerLogicException
     *
     * @return array<string, mixed>
     */
    public function normalizeLink(string $path, object $object, NormalizerContextInterface $context): array
    {
        $callback = $this->callback;

        $link = $callback($path, $object, $context);

        if (!$link instanceof LinkInterface) {
            $type = is_object($link) ? get_class($link) : gettype($link);

            throw SerializerLogicException::createInvalidLinkTypeReturned($path, $type);
        }

        return (new LinkNormalizer($link))->normalizeLink($path, $object, $context);
    }
}
