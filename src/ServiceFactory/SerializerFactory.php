<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\ServiceFactory;

use Chubbyphp\Laminas\Config\Factory\AbstractFactory;
use Chubbyphp\Serialization\Encoder\EncoderInterface;
use Chubbyphp\Serialization\Normalizer\NormalizerInterface;
use Chubbyphp\Serialization\Serializer;
use Chubbyphp\Serialization\SerializerInterface;
use Psr\Container\ContainerInterface;

final class SerializerFactory extends AbstractFactory
{
    public function __invoke(ContainerInterface $container): SerializerInterface
    {
        /** @var NormalizerInterface $normalizer */
        $normalizer = $this->resolveDependency($container, NormalizerInterface::class, NormalizerFactory::class);

        /** @var EncoderInterface $encoder */
        $encoder = $this->resolveDependency($container, EncoderInterface::class, EncoderFactory::class);

        return new Serializer($normalizer, $encoder);
    }
}
