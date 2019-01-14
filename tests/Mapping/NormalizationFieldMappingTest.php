<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization\Mapping;

use Chubbyphp\Mock\MockByCallsTrait;
use Chubbyphp\Serialization\Mapping\NormalizationFieldMapping;
use Chubbyphp\Serialization\Normalizer\FieldNormalizerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Chubbyphp\Serialization\Mapping\NormalizationFieldMapping
 */
class NormalizationFieldMappingTest extends TestCase
{
    use MockByCallsTrait;

    public function testGetName()
    {
        /** @var FieldNormalizerInterface|MockObject $fieldNormalizer */
        $fieldNormalizer = $this->getMockByCalls(FieldNormalizerInterface::class);

        $fieldMapping = new NormalizationFieldMapping('name', ['group1'], $fieldNormalizer);

        self::assertSame('name', $fieldMapping->getName());
    }

    public function testGetGroups()
    {
        /** @var FieldNormalizerInterface|MockObject $fieldNormalizer */
        $fieldNormalizer = $this->getMockByCalls(FieldNormalizerInterface::class);

        $fieldMapping = new NormalizationFieldMapping('name', ['group1'], $fieldNormalizer);

        self::assertSame(['group1'], $fieldMapping->getGroups());
    }

    public function testGetFieldNormalizer()
    {
        /** @var FieldNormalizerInterface|MockObject $fieldNormalizer */
        $fieldNormalizer = $this->getMockByCalls(FieldNormalizerInterface::class);

        $fieldMapping = new NormalizationFieldMapping('name', ['group1'], $fieldNormalizer);

        self::assertSame($fieldNormalizer, $fieldMapping->getFieldNormalizer());
    }
}
