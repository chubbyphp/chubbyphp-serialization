<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization\Unit\DependencyInjection;

use Chubbyphp\Serialization\DependencyInjection\SerializationCompilerPass;
use Chubbyphp\Serialization\Encoder\Encoder;
use Chubbyphp\Serialization\Encoder\JsonTypeEncoder;
use Chubbyphp\Serialization\Mapping\NormalizationFieldMappingInterface;
use Chubbyphp\Serialization\Mapping\NormalizationLinkMappingInterface;
use Chubbyphp\Serialization\Mapping\NormalizationObjectMappingInterface;
use Chubbyphp\Serialization\Normalizer\Normalizer;
use Chubbyphp\Serialization\Normalizer\NormalizerObjectMappingRegistry;
use Chubbyphp\Serialization\Serializer;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * @covers \Chubbyphp\Serialization\DependencyInjection\SerializationCompilerPass
 *
 * @internal
 */
final class SerializationCompilerPassTest extends TestCase
{
    public function testProcess(): void
    {
        $stdClassMapping = $this->getStdClassMapping();
        $stdClassMappingClass = $stdClassMapping::class;

        $container = new ContainerBuilder();
        $container->addCompilerPass(new SerializationCompilerPass());

        $container
            ->register('stdclass', $stdClassMappingClass)
            ->addTag('chubbyphp.serializer.normalizer.objectmapping')
        ;

        $container
            ->register('chubbyphp.serializer.encoder.type.json', JsonTypeEncoder::class)
            ->addTag('chubbyphp.serializer.encoder.type')
        ;

        $container->compile();

        self::assertTrue($container->has('chubbyphp.serializer'));
        self::assertTrue($container->has('chubbyphp.serializer.normalizer'));
        self::assertTrue($container->has('chubbyphp.serializer.normalizer.objectmappingregistry'));
        self::assertTrue($container->has('chubbyphp.serializer.encoder'));

        /** @var Serializer $serializer */
        $serializer = $container->get('chubbyphp.serializer');

        /** @var Normalizer $normalizer */
        $normalizer = $container->get('chubbyphp.serializer.normalizer');

        /** @var NormalizerObjectMappingRegistry $objectMappingRegistry */
        $objectMappingRegistry = $container->get('chubbyphp.serializer.normalizer.objectmappingregistry');

        /** @var Encoder $encoder */
        $encoder = $container->get('chubbyphp.serializer.encoder');

        self::assertInstanceOf(Serializer::class, $serializer);
        self::assertInstanceOf(Normalizer::class, $normalizer);
        self::assertInstanceOf(NormalizerObjectMappingRegistry::class, $objectMappingRegistry);
        self::assertInstanceOf(Encoder::class, $encoder);

        self::assertSame('{"key":"value"}', $encoder->encode(['key' => 'value'], 'application/json'));

        self::assertSame(['_type' => 'stdClass'], $normalizer->normalize(new \stdClass()));
    }

    private function getStdClassMapping()
    {
        return new class() implements NormalizationObjectMappingInterface {
            public function getClass(): string
            {
                return \stdClass::class;
            }

            public function getNormalizationType(): string
            {
                return 'stdClass';
            }

            /**
             * @return array<int, NormalizationFieldMappingInterface>
             */
            public function getNormalizationFieldMappings(string $path): array
            {
                return [];
            }

            /**
             * @return array<int, NormalizationFieldMappingInterface>
             */
            public function getNormalizationEmbeddedFieldMappings(string $path): array
            {
                return [];
            }

            /**
             * @return array<int, NormalizationLinkMappingInterface>
             */
            public function getNormalizationLinkMappings(string $path): array
            {
                return [];
            }
        };
    }
}
