<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\ServiceFactory;

use Chubbyphp\DecodeEncode\Encoder\EncoderInterface;
use Chubbyphp\DecodeEncode\ServiceFactory\EncoderFactory;
use Chubbyphp\Laminas\Config\Factory\AbstractFactory;
use Chubbyphp\Serialization\Encoder\EncoderInterface as OldEncoderInterface;
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

        if ($container->has(OldEncoderInterface::class.$this->name)) {
            @trigger_error(
                sprintf(
                    '%s use %s',
                    OldEncoderInterface::class,
                    EncoderInterface::class
                ),
                E_USER_DEPRECATED
            );

            /** @var OldEncoderInterface $encoder */
            $encoder = $container->get(OldEncoderInterface::class.$this->name);
        } else {
            /** @var EncoderInterface $encoder */
            $encoder = $this->resolveDependency($container, EncoderInterface::class, EncoderFactory::class);
        }

        return new Serializer($normalizer, $encoder);
    }
}
