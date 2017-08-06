<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization\Mapping;

use Chubbyphp\Serialization\Mapping\FieldMappingInterface;
use Chubbyphp\Serialization\Mapping\LazyObjectMapping;
use Chubbyphp\Serialization\Mapping\LinkMappingInterface;
use Chubbyphp\Serialization\Mapping\ObjectMappingInterface;
use Interop\Container\ContainerInterface;

/**
 * @covers \Chubbyphp\Serialization\Mapping\LazyObjectMapping
 */
final class LazyObjectMappingTest extends \PHPUnit_Framework_TestCase
{
    public function testInvoke()
    {
        $class = 'class';
        $type = 'type';
        $fieldMappings = [$this->getFieldMapping()];
        $embeddedFieldMappings = [$this->getFieldMapping()];
        $linkMappings = [$this->getLinkMapping()];

        $container = $this->getContainer([
            'service' => $this->getObjectMapping($class, $type, $fieldMappings, $embeddedFieldMappings, $linkMappings),
        ]);

        $objectMapping = new LazyObjectMapping($container, 'service', 'class');

        self::assertSame($class, $objectMapping->getClass());
        self::assertSame($type, $objectMapping->getType());
        self::assertSame($fieldMappings, $objectMapping->getFieldMappings());
        self::assertSame($embeddedFieldMappings, $objectMapping->getEmbeddedFieldMappings());
        self::assertSame($linkMappings, $objectMapping->getLinkMappings());
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
     * @param string $class
     * @param string $type
     * @param array  $fieldMappings
     * @param array  $embeddedFieldMappings
     * @param array  $linkMappings
     *
     * @return ObjectMappingInterface
     */
    private function getObjectMapping(
        string $class,
        string $type,
        array $fieldMappings,
        array $embeddedFieldMappings,
        array $linkMappings
    ): ObjectMappingInterface {
        /** @var ObjectMappingInterface|\PHPUnit_Framework_MockObject_MockObject $mapping */
        $mapping = $this
            ->getMockBuilder(ObjectMappingInterface::class)
            ->setMethods([
                'getClass',
                'getType',
                'getFieldMappings',
                'getEmbeddedFieldMappings',
                'getLinkMappings',
            ])
            ->getMockForAbstractClass()
        ;

        $mapping->expects(self::any())->method('getClass')->willReturn($class);
        $mapping->expects(self::any())->method('getType')->willReturn($type);
        $mapping->expects(self::any())->method('getFieldMappings')->willReturn($fieldMappings);
        $mapping->expects(self::any())->method('getEmbeddedFieldMappings')->willReturn($embeddedFieldMappings);
        $mapping->expects(self::any())->method('getLinkMappings')->willReturn($linkMappings);

        return $mapping;
    }

    /**
     * @return FieldMappingInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private function getFieldMapping(): FieldMappingInterface
    {
        return $this->getMockBuilder(FieldMappingInterface::class)->getMockForAbstractClass();
    }

    /**
     * @return LinkMappingInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private function getLinkMapping(): LinkMappingInterface
    {
        return $this->getMockBuilder(LinkMappingInterface::class)->getMockForAbstractClass();
    }
}
