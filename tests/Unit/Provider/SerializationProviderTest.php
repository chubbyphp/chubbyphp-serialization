<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization\Unit\Provider;

use Chubbyphp\Serialization\Encoder\Encoder;
use Chubbyphp\Serialization\Encoder\JsonTypeEncoder;
use Chubbyphp\Serialization\Encoder\JsonxTypeEncoder;
use Chubbyphp\Serialization\Encoder\TypeEncoderInterface;
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
 *
 * @internal
 */
final class SerializationProviderTest extends TestCase
{
    public function testRegister(): void
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
        self::assertIsArray($container['serializer.normalizer.objectmappings']);

        self::assertInstanceOf(Encoder::class, $container['serializer.encoder']);
        self::assertIsArray($container['serializer.encodertypes']);

        /** @var array<int, TypeEncoderInterface> $encoderTypes */
        $encoderTypes = $container['serializer.encodertypes'];

        self::assertInstanceOf(JsonTypeEncoder::class, array_shift($encoderTypes));

        $jsonxTypeEncoder1 = array_shift($encoderTypes);
        self::assertInstanceOf(JsonxTypeEncoder::class, $jsonxTypeEncoder1);

        self::assertSame('application/x-jsonx', $jsonxTypeEncoder1->getContentType());

        $jsonxTypeEncoder2 = array_shift($encoderTypes);
        self::assertInstanceOf(JsonxTypeEncoder::class, $jsonxTypeEncoder2);

        self::assertSame('application/jsonx+xml', $jsonxTypeEncoder2->getContentType());

        self::assertInstanceOf(UrlEncodedTypeEncoder::class, array_shift($encoderTypes));
        self::assertInstanceOf(XmlTypeEncoder::class, array_shift($encoderTypes));
        self::assertInstanceOf(YamlTypeEncoder::class, array_shift($encoderTypes));
    }
}
