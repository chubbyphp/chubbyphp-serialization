<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\ServiceFactory;

use Chubbyphp\Laminas\Config\Factory\AbstractFactory;
use Chubbyphp\Serialization\Encoder\Encoder;
use Chubbyphp\Serialization\Encoder\EncoderInterface;
use Chubbyphp\Serialization\Encoder\TypeEncoderInterface;
use Psr\Container\ContainerInterface;

/**
 * @deprecated \Chubbyphp\DecodeEncode\ServiceFactory\EncoderFactory
 */
final class EncoderFactory extends AbstractFactory
{
    public function __invoke(ContainerInterface $container): EncoderInterface
    {
        return new Encoder(
            $container->get(TypeEncoderInterface::class.'[]'.$this->name)
        );
    }
}
