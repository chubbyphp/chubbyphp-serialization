<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization\Mapping;

use Chubbyphp\Serialization\Mapping\FieldMapping;
use Chubbyphp\Serialization\Serializer\Field\FieldSerializerInterface;

/**
 * @covers \Chubbyphp\Serialization\Mapping\FieldMapping
 */
final class FieldMappingTest extends \PHPUnit_Framework_TestCase
{
    public function test()
    {
        $name = 'field1';
        $fieldSerializer = $this->getFieldSerializer();

        $fieldMapping = new FieldMapping($name, $fieldSerializer);

        self::assertSame($name, $fieldMapping->getName());
        self::assertSame($fieldSerializer, $fieldMapping->getFieldSerializer());
    }

    /**
     * @return FieldSerializerInterface
     */
    private function getFieldSerializer(): FieldSerializerInterface
    {
        return $this->getMockBuilder(FieldSerializerInterface::class)->getMockForAbstractClass();
    }
}
