<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Provider;

use Chubbyphp\Serialization\Registry\ObjectMappingRegistry;
use Chubbyphp\Serialization\Serializer;
use Chubbyphp\Serialization\Transformer\JsonTransformer;
use Chubbyphp\Serialization\Transformer\UrlEncodedTransformer;
use Chubbyphp\Serialization\Transformer\XmlTransformer;
use Chubbyphp\Serialization\Transformer\YamlTransformer;
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

        $container['serializer.transformer.json'] = function () {
            return new JsonTransformer();
        };

        $container['serializer.transformer.urlencoded'] = function () {
            return new UrlEncodedTransformer();
        };

        $container['serializer.transformer.xml'] = function () {
            return new XmlTransformer();
        };

        $container['serializer.transformer.yaml'] = function () {
            return new YamlTransformer();
        };
    }
}
