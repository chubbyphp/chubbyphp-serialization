<?php

namespace Chubbyphp\Tests\Serialization\Provider;

use Chubbyphp\Serialization\Encoder\Encoder;
use Chubbyphp\Serialization\Encoder\JsonTypeEncoder;
use Chubbyphp\Serialization\Encoder\JsonxTypeEncoder;
use Chubbyphp\Serialization\Encoder\UrlEncodedTypeEncoder;
use Chubbyphp\Serialization\Encoder\XmlTypeEncoder;
use Chubbyphp\Serialization\Encoder\YamlTypeEncoder;
use Chubbyphp\Serialization\Normalizer\Normalizer;
use Chubbyphp\Serialization\Normalizer\NormalizerObjectMappingRegistry;
use Chubbyphp\Serialization\Provider\SerializationProvider;
use Chubbyphp\Serialization\Serializer;
use PHPUnit\Framework\TestCase;
use Pimple\Container;

/**
 * @covers \Chubbyphp\Serialization\Provider\SerializationProvider
 */
final class SerializationProviderTest extends TestCase
{
    public function testRegister()
    {
        $container = new Container();
        $container->register(new SerializationProvider());

        self::assertTrue(isset($container['serializer']));

        self::assertTrue(isset($container['serializer.normalizer']));
        self::assertTrue(isset($container['serializer.normalizer.objectmappingregistry']));
        self::assertTrue(isset($container['serializer.normalizer.objectmappings']));

        self::assertTrue(isset($container['serializer.encoder']));
        self::assertTrue(isset($container['serializer.encodertypes']));

        self::assertInstanceOf(Serializer::class, $container['serializer']);

        self::assertInstanceOf(Normalizer::class, $container['serializer.normalizer']);
        self::assertInstanceOf(NormalizerObjectMappingRegistry::class, $container['serializer.normalizer.objectmappingregistry']);
        self::assertInternalType('array', $container['serializer.normalizer.objectmappings']);

        self::assertInstanceOf(Encoder::class, $container['serializer.encoder']);
        self::assertInternalType('array', $container['serializer.encodertypes']);
        self::assertInstanceOf(JsonTypeEncoder::class, $container['serializer.encodertypes'][0]);
        self::assertInstanceOf(JsonxTypeEncoder::class, $container['serializer.encodertypes'][1]);
        self::assertInstanceOf(UrlEncodedTypeEncoder::class, $container['serializer.encodertypes'][2]);
        self::assertInstanceOf(XmlTypeEncoder::class, $container['serializer.encodertypes'][3]);
        self::assertInstanceOf(YamlTypeEncoder::class, $container['serializer.encodertypes'][4]);
    }
}
