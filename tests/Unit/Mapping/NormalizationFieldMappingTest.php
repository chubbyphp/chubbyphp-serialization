<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization\Unit\Mapping;

use Chubbyphp\Mock\MockObjectBuilder;
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
    public function testGetName(): void
    {
        $builder = new MockObjectBuilder();

        /** @var FieldNormalizerInterface|MockObject $fieldNormalizer */
        $fieldNormalizer = $builder->create(FieldNormalizerInterface::class, []);

        $fieldMapping = new NormalizationFieldMapping('name', $fieldNormalizer);

        self::assertSame('name', $fieldMapping->getName());
    }

    public function testGetFieldNormalizer(): void
    {
        $builder = new MockObjectBuilder();

        /** @var FieldNormalizerInterface|MockObject $fieldNormalizer */
        $fieldNormalizer = $builder->create(FieldNormalizerInterface::class, []);

        $fieldMapping = new NormalizationFieldMapping('name', $fieldNormalizer);

        self::assertSame($fieldNormalizer, $fieldMapping->getFieldNormalizer());
    }

    public function testGetPolicy(): void
    {
        $builder = new MockObjectBuilder();

        /** @var FieldNormalizerInterface|MockObject $fieldNormalizer */
        $fieldNormalizer = $builder->create(FieldNormalizerInterface::class, []);

        /** @var MockObject|PolicyInterface $policy */
        $policy = $builder->create(PolicyInterface::class, []);

        $fieldMapping = new NormalizationFieldMapping('name', $fieldNormalizer, $policy);

        self::assertSame($policy, $fieldMapping->getPolicy());
    }
}
