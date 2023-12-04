<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization\Unit\ServiceProvider;

use Chubbyphp\DecodeEncode\Encoder\Encoder;
use Chubbyphp\Mock\MockByCallsTrait;
use Chubbyphp\Serialization\Normalizer\Normalizer;
use Chubbyphp\Serialization\Normalizer\NormalizerObjectMappingRegistry;
use Chubbyphp\Serialization\Serializer;
use Chubbyphp\Serialization\ServiceProvider\SerializationServiceProvider;
use PHPUnit\Framework\TestCase;
use Pimple\Container;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

/**
 * @covers \Chubbyphp\Serialization\ServiceProvider\SerializationServiceProvider
 *
 * @internal
 */
final class SerializationServiceProviderTest extends TestCase
{
    use MockByCallsTrait;

    public function testRegister(): void
    {
        $container = new Container();
        $container->register(new SerializationServiceProvider());

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

        /** @var Normalizer $normalizer */
        $normalizer = $container['serializer.normalizer'];

        self::assertInstanceOf(Normalizer::class, $normalizer);

        $reflectionProperty = new \ReflectionProperty($normalizer, 'logger');
        $reflectionProperty->setAccessible(true);

        self::assertInstanceOf(NullLogger::class, $reflectionProperty->getValue($normalizer));
    }

    public function testRegisterWithDefinedLogger(): void
    {
        /** @var LoggerInterface|MockObject $logger */
        $logger = $this->getMockByCalls(LoggerInterface::class);

        $container = new Container([
            'logger' => $logger,
        ]);

        $container->register(new SerializationServiceProvider());

        /** @var Normalizer $normalizer */
        $normalizer = $container['serializer.normalizer'];

        self::assertInstanceOf(Normalizer::class, $normalizer);

        $reflectionProperty = new \ReflectionProperty($normalizer, 'logger');
        $reflectionProperty->setAccessible(true);

        self::assertSame($logger, $reflectionProperty->getValue($normalizer));
    }
}
