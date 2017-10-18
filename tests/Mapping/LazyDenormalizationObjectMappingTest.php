<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization\Mapping;

use Chubbyphp\Serialization\Mapping\NormalizationFieldMappingInterface;
use Chubbyphp\Serialization\Mapping\NormalizationObjectMappingInterface;
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
        $denormalizationFieldMappings = [$this->getNormalizationFieldMapping()];

        $factory = function () {
        };

        $container = $this->getContainer([
            'service' => $this->getNormalizationObjectMapping($factory, $denormalizationFieldMappings),
        ]);

        $objectMapping = new LazyNormalizationObjectMapping($container, 'service', \stdClass::class);

        self::assertEquals(\stdClass::class, $objectMapping->getClass());
        self::assertSame($factory, $objectMapping->getNormalizationFactory('path', 'type'));
        self::assertSame($denormalizationFieldMappings, $objectMapping->getNormalizationFieldMappings('path', 'type'));
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
     * @param callable $denormalizationFactory
     * @param array    $denormalizationFieldMappings
     *
     * @return NormalizationObjectMappingInterface
     */
    private function getNormalizationObjectMapping(
        callable $denormalizationFactory,
        array $denormalizationFieldMappings
    ): NormalizationObjectMappingInterface {
        /** @var NormalizationObjectMappingInterface|\PHPUnit_Framework_MockObject_MockObject $mapping */
        $mapping = $this
            ->getMockBuilder(NormalizationObjectMappingInterface::class)
            ->setMethods(['getNormalizationFactory', 'getNormalizationFieldMappings'])
            ->getMockForAbstractClass()
        ;

        $mapping->expects(self::any())->method('getNormalizationFactory')->willReturn($denormalizationFactory);
        $mapping->expects(self::any())->method('getNormalizationFieldMappings')->willReturn($denormalizationFieldMappings);

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
