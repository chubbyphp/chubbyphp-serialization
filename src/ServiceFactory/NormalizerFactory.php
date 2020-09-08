<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\ServiceFactory;

use Chubbyphp\Laminas\Config\Factory\AbstractFactory;
use Chubbyphp\Serialization\Normalizer\Normalizer;
use Chubbyphp\Serialization\Normalizer\NormalizerInterface;
use Chubbyphp\Serialization\Normalizer\NormalizerObjectMappingRegistryInterface;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

final class NormalizerFactory extends AbstractFactory
{
    public function __invoke(ContainerInterface $container): NormalizerInterface
    {
        /** @var NormalizerObjectMappingRegistryInterface $normalizerObjectMappingRegistry */
        $normalizerObjectMappingRegistry = $this->resolveDependency(
            $container,
            NormalizerObjectMappingRegistryInterface::class,
            NormalizerObjectMappingRegistryFactory::class
        );

        /** @var LoggerInterface $logger */
        $logger = $container->has(LoggerInterface::class) ? $container->get(LoggerInterface::class) : new NullLogger();

        return new Normalizer($normalizerObjectMappingRegistry, $logger);
    }
}
