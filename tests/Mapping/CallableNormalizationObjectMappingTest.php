<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization\Mapping;

use Chubbyphp\Mock\Call;
use Chubbyphp\Mock\MockByCallsTrait;
use Chubbyphp\Serialization\Mapping\CallableNormalizationObjectMapping;
use Chubbyphp\Serialization\Mapping\NormalizationFieldMappingInterface;
use Chubbyphp\Serialization\Mapping\NormalizationLinkMappingInterface;
use Chubbyphp\Serialization\Mapping\NormalizationObjectMappingInterface;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Chubbyphp\Serialization\Mapping\CallableNormalizationObjectMapping
 *
 * @internal
 */
class CallableNormalizationObjectMappingTest extends TestCase
{
    use MockByCallsTrait;

    public function testGetClass()
    {
        $mapping = new CallableNormalizationObjectMapping(\stdClass::class, function () {});

        self::assertSame(\stdClass::class, $mapping->getClass());
    }

    public function testGetNormalizationType()
    {
        $mapping = new CallableNormalizationObjectMapping(\stdClass::class, function () {
            return $this->getMockByCalls(NormalizationObjectMappingInterface::class, [
                Call::create('getNormalizationType')->with()->willReturn('type'),
            ]);
        });

        self::assertSame('type', $mapping->getNormalizationType());
    }

    public function testGetNormalizationFieldMappings()
    {
        $fieldMapping = $this->getMockByCalls(NormalizationFieldMappingInterface::class);

        $mapping = new CallableNormalizationObjectMapping(\stdClass::class, function () use ($fieldMapping) {
            return $this->getMockByCalls(NormalizationObjectMappingInterface::class, [
                Call::create('getNormalizationFieldMappings')->with('path')->willReturn([$fieldMapping]),
            ]);
        });

        self::assertSame($fieldMapping, $mapping->getNormalizationFieldMappings('path')[0]);
    }

    public function testGetNormalizationEmbeddedFieldMappings()
    {
        $fieldMapping = $this->getMockByCalls(NormalizationFieldMappingInterface::class);

        $mapping = new CallableNormalizationObjectMapping(\stdClass::class, function () use ($fieldMapping) {
            return $this->getMockByCalls(NormalizationObjectMappingInterface::class, [
                Call::create('getNormalizationEmbeddedFieldMappings')->with('path')->willReturn([$fieldMapping]),
            ]);
        });

        self::assertSame($fieldMapping, $mapping->getNormalizationEmbeddedFieldMappings('path')[0]);
    }

    public function testGetNormalizationLinkMappings()
    {
        $linkMapping = $this->getMockByCalls(NormalizationLinkMappingInterface::class);

        $mapping = new CallableNormalizationObjectMapping(\stdClass::class, function () use ($linkMapping) {
            return $this->getMockByCalls(NormalizationObjectMappingInterface::class, [
                Call::create('getNormalizationLinkMappings')->with('path')->willReturn([$linkMapping]),
            ]);
        });

        self::assertSame($linkMapping, $mapping->getNormalizationLinkMappings('path')[0]);
    }
}
