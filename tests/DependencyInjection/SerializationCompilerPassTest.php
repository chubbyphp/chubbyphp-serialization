<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization\DependencyInjection;

use Chubbyphp\Serialization\DependencyInjection\SerializationCompilerPass;
use Chubbyphp\Serialization\Encoder\Encoder;
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
 */
class SerializationCompilerPassTest extends TestCase
{
    public function testProcess()
    {
        $stdClassMapping = $this->getStdClassMapping();
        $stdClassMappingClass = get_class($stdClassMapping);

        $container = new ContainerBuilder();
        $container->addCompilerPass(new SerializationCompilerPass());

        $container
            ->register('stdclass', $stdClassMappingClass)
            ->addTag('serializer.normalizer.objectmapping');

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
        self::assertSame('key=value', $encoder->encode(['key' => 'value'], 'application/x-www-form-urlencoded'));
        self::assertSame(
            '<?xml version="1.0" encoding="UTF-8"?>'."\n"
            .'<object type="object"><key type="string">value</key></object>',
            $encoder->encode(['key' => 'value', '_type' => 'object'], 'application/xml')
        );
        self::assertSame('key: value', $encoder->encode(['key' => 'value'], 'application/x-yaml'));

        self::assertSame(['_type' => 'stdClass'], $normalizer->normalize(new \stdClass()));
    }

    private function getStdClassMapping()
    {
        return new class() implements NormalizationObjectMappingInterface {
            /**
             * @return string
             */
            public function getClass(): string
            {
                return \stdClass::class;
            }

            /**
             * @return string
             */
            public function getNormalizationType(): string
            {
                return 'stdClass';
            }

            /**
             * @param string $path
             *
             * @return NormalizationFieldMappingInterface[]
             */
            public function getNormalizationFieldMappings(string $path): array
            {
                return [];
            }

            /**
             * @param string $path
             *
             * @return NormalizationFieldMappingInterface[]
             */
            public function getNormalizationEmbeddedFieldMappings(string $path): array
            {
                return [];
            }

            /**
             * @param string $path
             *
             * @return NormalizationLinkMappingInterface[]
             */
            public function getNormalizationLinkMappings(string $path): array
            {
                return [];
            }
        };
    }
}
