<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Provider;

use Chubbyphp\Serialization\Registry\ObjectMappingRegistry;
use Chubbyphp\Serialization\Serializer;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

final class SerializationProvider implements ServiceProviderInterface
{
    /**
     * @param Container $container
     */
    public function register(Container $container)
    {
        $container['serializer.objectmappings'] = function () {
            return [];
        };

        $container['serializer.objectmappingregistry'] = function () use ($container) {
            return new ObjectMappingRegistry($container['serializer.objectmappings']);
        };

        $container['serializer'] = function () use ($container) {
            return new Serializer(
                $container['serializer.objectmappingregistry'],
                $container['logger'] ?? null
            );
        };
    }
}
