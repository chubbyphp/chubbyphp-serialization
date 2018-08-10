<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\DependencyInjection;

use Chubbyphp\Serialization\Encoder\Encoder;
use Chubbyphp\Serialization\Normalizer\Normalizer;
use Chubbyphp\Serialization\Normalizer\NormalizerObjectMappingRegistry;
use Chubbyphp\Serialization\Serializer;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Reference;

final class SerializationCompilerPass implements CompilerPassInterface
{
    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        $container->register('chubbyphp.serializer', Serializer::class)->setPublic(true)->setArguments([
            new Reference('chubbyphp.serializer.normalizer'),
            new Reference('chubbyphp.serializer.encoder'),
        ]);

        $container
            ->register('chubbyphp.serializer.normalizer', Normalizer::class)
            ->setPublic(true)
            ->setArguments([
                new Reference('chubbyphp.serializer.normalizer.objectmappingregistry'),
                new Reference('logger', ContainerInterface::IGNORE_ON_INVALID_REFERENCE),
            ]);

        $normalizerObjectMappingReferences = [];
        foreach ($container->findTaggedServiceIds('serializer.normalizer.objectmapping') as $id => $tags) {
            $normalizerObjectMappingReferences[] = new Reference($id);
        }

        $container
            ->register(
                'chubbyphp.serializer.normalizer.objectmappingregistry',
                NormalizerObjectMappingRegistry::class
            )
            ->setPublic(true)
            ->setArguments([$normalizerObjectMappingReferences]);

        $encoderTypeReferences = [];
        foreach ($container->findTaggedServiceIds('chubbyphp.serializer.encoder.type') as $id => $tags) {
            $encoderTypeReferences[] = new Reference($id);
        }

        $container
            ->register('chubbyphp.serializer.encoder', Encoder::class)
            ->setPublic(true)
            ->setArguments([$encoderTypeReferences]);
    }
}
