<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization\Registry;

use Chubbyphp\Serialization\Mapping\ObjectMappingInterface;
use Chubbyphp\Serialization\Registry\ObjectMappingRegistry;

/**
 * @covers \Chubbyphp\Serialization\Registry\ObjectMappingRegistry
 */
final class ObjectMappingRegistryTest extends \PHPUnit_Framework_TestCase
{
    public function testWithKnownObjectMappings()
    {
        $mappings = [
            $this->getObjectMapping('Namespace\To\MyModel1'),
            $this->getObjectMapping('Namespace\To\MyModel2'),
        ];

        $registry = new ObjectMappingRegistry($mappings);

        self::assertSame($mappings[0], $registry->getObjectMappingForClass('Namespace\To\MyModel1'));
        self::assertSame($mappings[1], $registry->getObjectMappingForClass('Namespace\To\MyModel2'));
    }

    public function testWithUnknownObjectMappings()
    {
        self::expectException(\InvalidArgumentException::class);
        self::expectExceptionMessage('There is no mapping for class: Namespace\To\MyModel1');

        $registry = new ObjectMappingRegistry([]);

        $registry->getObjectMappingForClass('Namespace\To\MyModel1');
    }

    /**
     * @param string $class
     *
     * @return ObjectMappingInterface
     */
    private function getObjectMapping(string $class): ObjectMappingInterface
    {
        /** @var ObjectMappingInterface|\PHPUnit_Framework_MockObject_MockObject $mapping */
        $mapping = $this->getMockBuilder(ObjectMappingInterface::class)->getMockForAbstractClass();

        $mapping->expects(self::any())->method('getClass')->willReturn($class);

        return $mapping;
    }
}
