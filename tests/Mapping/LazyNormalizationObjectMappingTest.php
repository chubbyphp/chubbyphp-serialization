<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization\Mapping;

use Chubbyphp\Serialization\Mapping\NormalizationFieldMappingInterface;
use Chubbyphp\Serialization\Mapping\LegacyNormalizationObjectMappingInterface;
use Chubbyphp\Serialization\Mapping\LazyNormalizationObjectMapping;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

/**
 * @covers \Chubbyphp\Serialization\Mapping\LazyNormalizationObjectMapping
 */
class LazyNormalizationObjectMappingTest extends TestCase
{
    public function testInvoke()
    {
        $normalizationType = 'type';
        $normalizationFieldMappings = [$this->getNormalizationFieldMapping()];
        $normalizationEmbeddedFieldMappings = [$this->getNormalizationFieldMapping()];
        $normalizationLinkMappings = [];

        $container = $this->getContainer([
            'service' => $this->getNormalizationObjectMapping(
                $normalizationType,
                $normalizationFieldMappings,
                $normalizationEmbeddedFieldMappings,
                $normalizationLinkMappings
            ),
        ]);

        $objectMapping = new LazyNormalizationObjectMapping($container, 'service', \stdClass::class);

        self::assertEquals(\stdClass::class, $objectMapping->getClass());
        self::assertSame($normalizationType, $objectMapping->getNormalizationType());
        self::assertSame($normalizationFieldMappings, $objectMapping->getNormalizationFieldMappings('path'));
        self::assertSame($normalizationEmbeddedFieldMappings, $objectMapping->getNormalizationEmbeddedFieldMappings('path'));
        self::assertSame($normalizationLinkMappings, $objectMapping->getNormalizationLinkMappings('path'));
    }

    /**
     * @param array $services
     *
     * @return ContainerInterface
     */
    private function getContainer(array $services): ContainerInterface
    {
        /** @var ContainerInterface|\PHPUnit_Framework_MockObject_MockObject $container */
        $container = $this->getMockBuilder(ContainerInterface::class)->setMethods(['get'])->getMockForAbstractClass();

        $container
            ->expects(self::any())
            ->method('get')
            ->willReturnCallback(function (string $id) use ($services) {
                return $services[$id];
            })
        ;

        return $container;
    }

    /**
     * @param string $normalizationType
     * @param array  $normalizationFieldMappings
     * @param array  $normalizationEmbeddedFieldMappings
     * @param array  $normalizationLinkMappings
     *
     * @return LegacyNormalizationObjectMappingInterface
     */
    private function getNormalizationObjectMapping(
        string $normalizationType,
        array $normalizationFieldMappings,
        array $normalizationEmbeddedFieldMappings,
        array $normalizationLinkMappings
    ): LegacyNormalizationObjectMappingInterface {
        /** @var LegacyNormalizationObjectMappingInterface|\PHPUnit_Framework_MockObject_MockObject $mapping */
        $mapping = $this
            ->getMockBuilder(LegacyNormalizationObjectMappingInterface::class)
            ->setMethods([
                'getNormalizationType',
                'getNormalizationFieldMappings',
                'getNormalizationEmbeddedFieldMappings',
                'getNormalizationLinkMappings',
            ])
            ->getMockForAbstractClass()
        ;

        $mapping->expects(self::any())->method('getNormalizationType')->willReturn($normalizationType);

        $mapping->expects(self::any())
            ->method('getNormalizationFieldMappings')
            ->with(self::equalTo('path'))
            ->willReturn($normalizationFieldMappings);

        $mapping->expects(self::any())
            ->method('getNormalizationEmbeddedFieldMappings')
            ->with(self::equalTo('path'))
            ->willReturn($normalizationEmbeddedFieldMappings);

        $mapping->expects(self::any())
            ->method('getNormalizationLinkMappings')
            ->with(self::equalTo('path'))
            ->willReturn($normalizationLinkMappings);

        return $mapping;
    }

    /**
     * @return NormalizationFieldMappingInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private function getNormalizationFieldMapping(): NormalizationFieldMappingInterface
    {
        return $this->getMockBuilder(NormalizationFieldMappingInterface::class)->getMockForAbstractClass();
    }
}
