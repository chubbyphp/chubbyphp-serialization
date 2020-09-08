<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Container;

use Chubbyphp\Serialization\Normalizer\Normalizer;
use Chubbyphp\Serialization\Normalizer\NormalizerInterface;
use Chubbyphp\Serialization\Normalizer\NormalizerObjectMappingRegistryInterface;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

/**
 * @deprecated \Chubbyphp\Serialization\ServiceFactory\NormalizerFactory
 */
final class NormalizerFactory
{
    public function __invoke(ContainerInterface $container): NormalizerInterface
    {
        return new Normalizer(
            $container->get(NormalizerObjectMappingRegistryInterface::class),
            $container->get(LoggerInterface::class)
        );
    }
}
