<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\ServiceFactory;

use Chubbyphp\Laminas\Config\Factory\AbstractFactory;
use Chubbyphp\Serialization\Mapping\NormalizationObjectMappingInterface;
use Chubbyphp\Serialization\Normalizer\NormalizerObjectMappingRegistry;
use Chubbyphp\Serialization\Normalizer\NormalizerObjectMappingRegistryInterface;
use Psr\Container\ContainerInterface;

final class NormalizerObjectMappingRegistryFactory extends AbstractFactory
{
    public function __invoke(ContainerInterface $container): NormalizerObjectMappingRegistryInterface
    {
        return new NormalizerObjectMappingRegistry(
            $container->get(NormalizationObjectMappingInterface::class.'[]'.$this->name)
        );
    }
}
