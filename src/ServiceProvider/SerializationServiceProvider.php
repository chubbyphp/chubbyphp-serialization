<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\ServiceProvider;

use Chubbyphp\Serialization\Encoder\Encoder;
use Chubbyphp\Serialization\Normalizer\Normalizer;
use Chubbyphp\Serialization\Normalizer\NormalizerObjectMappingRegistry;
use Chubbyphp\Serialization\Serializer;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

final class SerializationServiceProvider implements ServiceProviderInterface
{
    public function register(Container $container): void
    {
        $container['serializer'] = static function () use ($container) {
            return new Serializer($container['serializer.normalizer'], $container['serializer.encoder']);
        };

        $container['serializer.normalizer'] = static function () use ($container) {
            return new Normalizer(
                $container['serializer.normalizer.objectmappingregistry'],
                $container['logger'] ?? null
            );
        };

        $container['serializer.normalizer.objectmappingregistry'] = static function () use ($container) {
            return new NormalizerObjectMappingRegistry($container['serializer.normalizer.objectmappings']);
        };

        $container['serializer.normalizer.objectmappings'] = static function () {
            return [];
        };

        $container['serializer.encoder'] = static function () use ($container) {
            return new Encoder($container['serializer.encodertypes']);
        };

        $container['serializer.encodertypes'] = static function () {
            return [];
        };
    }
}
