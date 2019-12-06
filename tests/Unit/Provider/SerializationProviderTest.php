<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization\Unit\Provider;

use Chubbyphp\Mock\MockByCallsTrait;
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
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

/**
 * @covers \Chubbyphp\Serialization\Provider\SerializationProvider
 *
 * @internal
 */
final class SerializationProviderTest extends TestCase
{
    use MockByCallsTrait;

    public function testAdapter(): void
    {
        error_clear_last();

        new SerializationProvider();

        $error = error_get_last();

        self::assertNotNull($error);

        self::assertSame(E_USER_DEPRECATED, $error['type']);
        self::assertSame(
            'Use "Chubbyphp\Serialization\ServiceProvider\SerializationServiceProvider" instead.',
            $error['message']
        );
    }

    public function testRegister(): void
    {
        $container = new Container();
        $container->register(new SerializationProvider());

        error_clear_last();

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
        self::assertInstanceOf(JsonTypeEncoder::class, $container['serializer.encodertypes'][0]);
        self::assertInstanceOf(JsonxTypeEncoder::class, $container['serializer.encodertypes'][1]);
        self::assertInstanceOf(UrlEncodedTypeEncoder::class, $container['serializer.encodertypes'][2]);
        self::assertInstanceOf(XmlTypeEncoder::class, $container['serializer.encodertypes'][3]);
        self::assertInstanceOf(YamlTypeEncoder::class, $container['serializer.encodertypes'][4]);

        /** @var Normalizer $normalizer */
        $normalizer = $container['serializer.normalizer'];

        self::assertInstanceOf(Normalizer::class, $normalizer);

        $reflectionProperty = new \ReflectionProperty($normalizer, 'logger');
        $reflectionProperty->setAccessible(true);

        self::assertInstanceOf(NullLogger::class, $reflectionProperty->getValue($normalizer));

        $error = error_get_last();

        self::assertNotNull($error);

        self::assertSame(E_USER_DEPRECATED, $error['type']);
        self::assertSame(
            'Register the encoder types by yourself:'
                .' $container[\'serializer.encodertypes\'] = function () { return [new JsonTypeEncoder()]; };',
            $error['message']
        );
    }

    public function testRegisterWithDefinedLogger(): void
    {
        /** @var LoggerInterface|MockObject $logger */
        $logger = $this->getMockByCalls(LoggerInterface::class);

        $container = new Container([
            'logger' => $logger,
        ]);

        $container->register(new SerializationProvider());

        /** @var Normalizer $normalizer */
        $normalizer = $container['serializer.normalizer'];

        self::assertInstanceOf(Normalizer::class, $normalizer);

        $reflectionProperty = new \ReflectionProperty($normalizer, 'logger');
        $reflectionProperty->setAccessible(true);

        self::assertSame($logger, $reflectionProperty->getValue($normalizer));
    }
}
