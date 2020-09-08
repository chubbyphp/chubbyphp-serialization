<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Container;

use Chubbyphp\Serialization\Encoder\EncoderInterface;
use Chubbyphp\Serialization\Normalizer\NormalizerInterface;
use Chubbyphp\Serialization\Serializer;
use Chubbyphp\Serialization\SerializerInterface;
use Psr\Container\ContainerInterface;

/**
 * @deprecated \Chubbyphp\Serialization\ServiceFactory\SerializerFactory
 */
final class SerializerFactory
{
    public function __invoke(ContainerInterface $container): SerializerInterface
    {
        return new Serializer(
            $container->get(NormalizerInterface::class),
            $container->get(EncoderInterface::class)
        );
    }
}
