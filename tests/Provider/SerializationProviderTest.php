<?php

namespace Chubbyphp\Tests\Serialization\Provider;

use Chubbyphp\Serialization\Registry\ObjectMappingRegistry;
use Chubbyphp\Serialization\Provider\SerializationProvider;
use Chubbyphp\Serialization\Serializer;
use Chubbyphp\Serialization\Transformer;
use Chubbyphp\Serialization\Transformer\JsonTransformer;
use Chubbyphp\Serialization\Transformer\UrlEncodedTransformer;
use Chubbyphp\Serialization\Transformer\XmlTransformer;
use Chubbyphp\Serialization\Transformer\YamlTransformer;
use Pimple\Container;

/**
 * @covers \Chubbyphp\Serialization\Provider\SerializationProvider
 */
final class SerializationProviderTest extends \PHPUnit_Framework_TestCase
{
    public function testRegister()
    {
        $container = new Container();
        $container->register(new SerializationProvider());

        self::assertTrue(isset($container['serializer.objectmappings']));
        self::assertTrue(isset($container['serializer.objectmappingregistry']));
        self::assertTrue(isset($container['serializer']));

        self::assertTrue(isset($container['serializer.transformer']));
        self::assertTrue(isset($container['serializer.transformer.json']));
        self::assertTrue(isset($container['serializer.transformer.urlencoded']));
        self::assertTrue(isset($container['serializer.transformer.xml']));
        self::assertTrue(isset($container['serializer.transformer.yaml']));

        self::assertSame([], $container['serializer.objectmappings']);
        self::assertInstanceOf(ObjectMappingRegistry::class, $container['serializer.objectmappingregistry']);
        self::assertInstanceOf(Serializer::class, $container['serializer']);

        self::assertInstanceOf(Transformer::class, $container['serializer.transformer']);
        self::assertInstanceOf(JsonTransformer::class, $container['serializer.transformer.json']);
        self::assertInstanceOf(UrlEncodedTransformer::class, $container['serializer.transformer.urlencoded']);
        self::assertInstanceOf(XmlTransformer::class, $container['serializer.transformer.xml']);
        self::assertInstanceOf(YamlTransformer::class, $container['serializer.transformer.yaml']);
    }
}
