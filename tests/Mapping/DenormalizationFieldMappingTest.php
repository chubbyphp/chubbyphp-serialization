<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization\Mapping;

use Chubbyphp\Serialization\Normalizer\FieldNormalizerInterface;
use Chubbyphp\Serialization\Mapping\NormalizationFieldMapping;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Chubbyphp\Serialization\Mapping\NormalizationFieldMapping
 */
class NormalizationFieldMappingTest extends TestCase
{
    public function testGetName()
    {
        $fieldMapping = new NormalizationFieldMapping('name', ['group1'], $this->getFieldNormalizer());

        self::assertSame('name', $fieldMapping->getName());
    }

    public function testGetGroups()
    {
        $fieldMapping = new NormalizationFieldMapping('name', ['group1'], $this->getFieldNormalizer());

        self::assertSame(['group1'], $fieldMapping->getGroups());
    }

    public function testGetFieldNormalizer()
    {
        $fieldNormalizer = $this->getFieldNormalizer();

        $fieldMapping = new NormalizationFieldMapping('name', ['group1'], $fieldNormalizer);

        self::assertSame($fieldNormalizer, $fieldMapping->getFieldNormalizer());
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
