<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization\Mapping;

use Chubbyphp\Serialization\Normalizer\FieldNormalizerInterface;
use Chubbyphp\Serialization\Mapping\NormalizationFieldMappingBuilder;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Chubbyphp\Serialization\Mapping\NormalizationFieldMappingBuilder
 */
class NormalizationFieldMappingBuilderTest extends TestCase
{
    public function testGetDefaultMapping()
    {
        $fieldMapping = NormalizationFieldMappingBuilder::create('name')->getMapping();

        self::assertSame('name', $fieldMapping->getName());
        self::assertSame([], $fieldMapping->getGroups());
        self::assertInstanceOf(FieldNormalizerInterface::class, $fieldMapping->getFieldNormalizer());
    }

    public function testGetMapping()
    {
        $normalizer = $this->getFieldNormalizer();

        $fieldMapping = NormalizationFieldMappingBuilder::create('name')
            ->setGroups(['group1'])
            ->setFieldNormalizer($normalizer)
            ->getMapping();

        self::assertSame('name', $fieldMapping->getName());
        self::assertSame(['group1'], $fieldMapping->getGroups());
        self::assertSame($normalizer, $fieldMapping->getFieldNormalizer());
    }

    /**
     * @return FieldNormalizerInterface
     */
    private function getFieldNormalizer(): FieldNormalizerInterface
    {
        /** @var FieldNormalizerInterface|\PHPUnit_Framework_MockObject_MockObject $fieldNormalizer */
        $fieldNormalizer = $this->getMockBuilder(FieldNormalizerInterface::class)->getMockForAbstractClass();

        return $fieldNormalizer;
    }
}
