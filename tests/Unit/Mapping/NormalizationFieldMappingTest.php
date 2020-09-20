<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization\Unit\Mapping;

use Chubbyphp\Mock\MockByCallsTrait;
use Chubbyphp\Serialization\Mapping\NormalizationFieldMapping;
use Chubbyphp\Serialization\Normalizer\FieldNormalizerInterface;
use Chubbyphp\Serialization\Policy\PolicyInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Chubbyphp\Serialization\Mapping\NormalizationFieldMapping
 *
 * @internal
 */
final class NormalizationFieldMappingTest extends TestCase
{
    use MockByCallsTrait;

    public function testGetName(): void
    {
        /** @var FieldNormalizerInterface|MockObject $fieldNormalizer */
        $fieldNormalizer = $this->getMockByCalls(FieldNormalizerInterface::class);

        $fieldMapping = new NormalizationFieldMapping('name', $fieldNormalizer);

        self::assertSame('name', $fieldMapping->getName());
    }

    public function testGetFieldNormalizer(): void
    {
        /** @var FieldNormalizerInterface|MockObject $fieldNormalizer */
        $fieldNormalizer = $this->getMockByCalls(FieldNormalizerInterface::class);

        $fieldMapping = new NormalizationFieldMapping('name', $fieldNormalizer);

        self::assertSame($fieldNormalizer, $fieldMapping->getFieldNormalizer());
    }

    public function testGetPolicy(): void
    {
        /** @var FieldNormalizerInterface|MockObject $fieldNormalizer */
        $fieldNormalizer = $this->getMockByCalls(FieldNormalizerInterface::class);

        /** @var PolicyInterface|MockObject $policy */
        $policy = $this->getMockByCalls(PolicyInterface::class);

        $fieldMapping = new NormalizationFieldMapping('name', $fieldNormalizer, $policy);

        self::assertSame($policy, $fieldMapping->getPolicy());
    }
}
