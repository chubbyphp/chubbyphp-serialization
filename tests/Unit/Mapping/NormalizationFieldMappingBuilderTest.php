<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization\Unit\Mapping;

use Chubbyphp\Mock\MockByCallsTrait;
use Chubbyphp\Serialization\Mapping\NormalizationFieldMappingBuilder;
use Chubbyphp\Serialization\Normalizer\CallbackFieldNormalizer;
use Chubbyphp\Serialization\Normalizer\DateTimeFieldNormalizer;
use Chubbyphp\Serialization\Normalizer\FieldNormalizer;
use Chubbyphp\Serialization\Normalizer\FieldNormalizerInterface;
use Chubbyphp\Serialization\Normalizer\Relation\EmbedManyFieldNormalizer;
use Chubbyphp\Serialization\Normalizer\Relation\EmbedOneFieldNormalizer;
use Chubbyphp\Serialization\Normalizer\Relation\ReferenceManyFieldNormalizer;
use Chubbyphp\Serialization\Normalizer\Relation\ReferenceOneFieldNormalizer;
use Chubbyphp\Serialization\Policy\NullPolicy;
use Chubbyphp\Serialization\Policy\PolicyInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Chubbyphp\Serialization\Mapping\NormalizationFieldMappingBuilder
 *
 * @internal
 */
final class NormalizationFieldMappingBuilderTest extends TestCase
{
    use MockByCallsTrait;

    public function testGetDefaultMapping(): void
    {
        $fieldMapping = NormalizationFieldMappingBuilder::create('name')->getMapping();

        self::assertSame('name', $fieldMapping->getName());
        self::assertInstanceOf(FieldNormalizer::class, $fieldMapping->getFieldNormalizer());
        self::assertInstanceOf(NullPolicy::class, $fieldMapping->getPolicy());
    }

    public function testGetMappingWithNormalizer(): void
    {
        /** @var FieldNormalizerInterface|MockObject $fieldNormalizer */
        $fieldNormalizer = $this->getMockByCalls(FieldNormalizerInterface::class);

        $fieldMapping = NormalizationFieldMappingBuilder::create('name', $fieldNormalizer)->getMapping();

        self::assertSame('name', $fieldMapping->getName());
        self::assertSame($fieldNormalizer, $fieldMapping->getFieldNormalizer());
        self::assertInstanceOf(NullPolicy::class, $fieldMapping->getPolicy());
    }

    public function testGetDefaultMappingForCallback(): void
    {
        $fieldMapping = NormalizationFieldMappingBuilder::createCallback('name', function (): void {})->getMapping();

        self::assertSame('name', $fieldMapping->getName());
        self::assertInstanceOf(CallbackFieldNormalizer::class, $fieldMapping->getFieldNormalizer());
        self::assertInstanceOf(NullPolicy::class, $fieldMapping->getPolicy());
    }

    public function testGetDefaultMappingForDateTime(): void
    {
        $fieldMapping = NormalizationFieldMappingBuilder::createDateTime('name')->getMapping();

        self::assertSame('name', $fieldMapping->getName());
        self::assertInstanceOf(DateTimeFieldNormalizer::class, $fieldMapping->getFieldNormalizer());
        self::assertInstanceOf(NullPolicy::class, $fieldMapping->getPolicy());
    }

    public function testGetDefaultMappingForDateTimeWithFormat(): void
    {
        $fieldMapping = NormalizationFieldMappingBuilder::createDateTime('name', \DateTime::ATOM)->getMapping();

        /** @var DateTimeFieldNormalizer $fieldNormalizer */
        $fieldNormalizer = $fieldMapping->getFieldNormalizer();

        self::assertSame('name', $fieldMapping->getName());
        self::assertInstanceOf(DateTimeFieldNormalizer::class, $fieldNormalizer);
        self::assertInstanceOf(NullPolicy::class, $fieldMapping->getPolicy());

        $reflection = new \ReflectionProperty($fieldNormalizer, 'format');
        $reflection->setAccessible(true);

        self::assertSame(\DateTime::ATOM, $reflection->getValue($fieldNormalizer));
    }

    public function testGetDefaultMappingForEmbedMany(): void
    {
        $fieldMapping = NormalizationFieldMappingBuilder::createEmbedMany('name')->getMapping();

        self::assertSame('name', $fieldMapping->getName());
        self::assertInstanceOf(EmbedManyFieldNormalizer::class, $fieldMapping->getFieldNormalizer());
        self::assertInstanceOf(NullPolicy::class, $fieldMapping->getPolicy());
    }

    public function testGetDefaultMappingForEmbedOne(): void
    {
        $fieldMapping = NormalizationFieldMappingBuilder::createEmbedOne('name')->getMapping();

        self::assertSame('name', $fieldMapping->getName());
        self::assertInstanceOf(EmbedOneFieldNormalizer::class, $fieldMapping->getFieldNormalizer());
        self::assertInstanceOf(NullPolicy::class, $fieldMapping->getPolicy());
    }

    public function testGetDefaultMappingForReferenceMany(): void
    {
        $fieldMapping = NormalizationFieldMappingBuilder::createReferenceMany('name')->getMapping();

        self::assertSame('name', $fieldMapping->getName());
        self::assertInstanceOf(ReferenceManyFieldNormalizer::class, $fieldMapping->getFieldNormalizer());
        self::assertInstanceOf(NullPolicy::class, $fieldMapping->getPolicy());
    }

    public function testGetDefaultMappingForReferenceOne(): void
    {
        $fieldMapping = NormalizationFieldMappingBuilder::createReferenceOne('name')->getMapping();

        self::assertSame('name', $fieldMapping->getName());
        self::assertInstanceOf(ReferenceOneFieldNormalizer::class, $fieldMapping->getFieldNormalizer());
        self::assertInstanceOf(NullPolicy::class, $fieldMapping->getPolicy());
    }

    public function testGetMapping(): void
    {
        /** @var FieldNormalizerInterface|MockObject $fieldNormalizer */
        $fieldNormalizer = $this->getMockByCalls(FieldNormalizerInterface::class);

        /** @var PolicyInterface|MockObject $policy */
        $policy = $this->getMockByCalls(PolicyInterface::class);

        $fieldMapping = NormalizationFieldMappingBuilder::create('name', $fieldNormalizer)
            ->setPolicy($policy)
            ->getMapping()
        ;

        self::assertSame('name', $fieldMapping->getName());
        self::assertSame($fieldNormalizer, $fieldMapping->getFieldNormalizer());
        self::assertSame($policy, $fieldMapping->getPolicy());
    }
}
