<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization\Mapping;

use Chubbyphp\Mock\Call;
use Chubbyphp\Mock\MockByCallsTrait;
use Chubbyphp\Serialization\Mapping\LazyNormalizationObjectMapping;
use Chubbyphp\Serialization\Mapping\NormalizationFieldMappingInterface;
use Chubbyphp\Serialization\Mapping\NormalizationLinkMappingInterface;
use Chubbyphp\Serialization\Mapping\NormalizationObjectMappingInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

/**
 * @covers \Chubbyphp\Serialization\Mapping\LazyNormalizationObjectMapping
 *
 * @internal
 */
class LazyNormalizationObjectMappingTest extends TestCase
{
    use MockByCallsTrait;

    public function testInvoke()
    {
        $normalizationFieldMappings = [$this->getMockByCalls(NormalizationFieldMappingInterface::class)];

        $normalizationEmbeddedFieldMappings = [$this->getMockByCalls(NormalizationFieldMappingInterface::class)];

        $normalizationLinkMappings = [$this->getMockByCalls(NormalizationLinkMappingInterface::class)];

        /** @var NormalizationObjectMappingInterface|MockObject $normalizationObjectMapping */
        $normalizationObjectMapping = $this->getMockByCalls(NormalizationObjectMappingInterface::class, [
            Call::create('getNormalizationType')->with()->willReturn('type'),
            Call::create('getNormalizationFieldMappings')
                ->with('path')
                ->willReturn($normalizationFieldMappings),
            Call::create('getNormalizationEmbeddedFieldMappings')
                ->with('path')
                ->willReturn($normalizationEmbeddedFieldMappings),
            Call::create('getNormalizationLinkMappings')
                ->with('path')
                ->willReturn($normalizationLinkMappings),
        ]);

        /** @var ContainerInterface|MockObject $container */
        $container = $this->getMockByCalls(ContainerInterface::class, [
            Call::create('get')->with('service')->willReturn($normalizationObjectMapping),
            Call::create('get')->with('service')->willReturn($normalizationObjectMapping),
            Call::create('get')->with('service')->willReturn($normalizationObjectMapping),
            Call::create('get')->with('service')->willReturn($normalizationObjectMapping),
        ]);

        $objectMapping = new LazyNormalizationObjectMapping($container, 'service', \stdClass::class);

        self::assertEquals(\stdClass::class, $objectMapping->getClass());
        self::assertSame('type', $objectMapping->getNormalizationType());
        self::assertSame($normalizationFieldMappings, $objectMapping->getNormalizationFieldMappings('path'));
        self::assertSame(
            $normalizationEmbeddedFieldMappings,
            $objectMapping->getNormalizationEmbeddedFieldMappings('path')
        );
        self::assertSame($normalizationLinkMappings, $objectMapping->getNormalizationLinkMappings('path'));
    }
}
