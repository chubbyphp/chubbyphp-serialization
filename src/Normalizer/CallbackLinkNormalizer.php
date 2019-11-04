<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Normalizer;

use Chubbyphp\Serialization\Policy\PolicyInterface;
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
     * @param object $object
     *
     * @throws SerializerLogicException
     *
     * @return array|null
     */
    public function normalizeLink(string $path, $object, NormalizerContextInterface $context)
    {
        $callback = $this->callback;

        $link = $callback($path, $object, $context);

        if (null === $link) {
            @trigger_error(
                sprintf(
                    'If a link should not be serialized under certain conditions, use a "%s" instead',
                    PolicyInterface::class
                ),
                E_USER_DEPRECATED
            );

            return null;
        }

        if (!$link instanceof LinkInterface) {
            $type = is_object($link) ? get_class($link) : gettype($link);

            throw SerializerLogicException::createInvalidLinkTypeReturned($path, $type);
        }

        return (new LinkNormalizer($link))->normalizeLink($path, $object, $context);
    }
}
