<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization\Unit\Normalizer;

use Chubbyphp\Mock\Call;
use Chubbyphp\Mock\MockByCallsTrait;
use Chubbyphp\Serialization\Accessor\AccessorInterface;
use Chubbyphp\Serialization\Normalizer\NormalizerContextInterface;
use Chubbyphp\Serialization\Normalizer\Relation\ReferenceOneFieldNormalizer;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Chubbyphp\Serialization\Normalizer\Relation\ReferenceOneFieldNormalizer
 *
 * @internal
 */
final class ReferenceOneFieldNormalizerTest extends TestCase
{
    use MockByCallsTrait;

    public function testNormalizeFieldWithNull(): void
    {
        $object = new \stdClass();

        /** @var AccessorInterface|MockObject $identifierAccessor */
        $identifierAccessor = $this->getMockByCalls(AccessorInterface::class);

        /** @var AccessorInterface|MockObject $accessor */
        $accessor = $this->getMockByCalls(AccessorInterface::class, [
            Call::create('getValue')->with($object)->willReturn(null),
        ]);

        /** @var MockObject|NormalizerContextInterface $context */
        $context = $this->getMockByCalls(NormalizerContextInterface::class);

        $fieldNormalizer = new ReferenceOneFieldNormalizer($identifierAccessor, $accessor);

        $data = $fieldNormalizer->normalizeField(
            'relation',
            $object,
            $context
        );

        self::assertNull($data);
    }

    public function testNormalizeFieldWithObject(): void
    {
        $relation = new \stdClass();

        $object = new \stdClass();

        /** @var AccessorInterface|MockObject $identifierAccessor */
        $identifierAccessor = $this->getMockByCalls(AccessorInterface::class, [
            Call::create('getValue')->with($relation)->willReturn('id1'),
        ]);

        /** @var AccessorInterface|MockObject $accessor */
        $accessor = $this->getMockByCalls(AccessorInterface::class, [
            Call::create('getValue')->with($object)->willReturn($relation),
        ]);

        /** @var MockObject|NormalizerContextInterface $context */
        $context = $this->getMockByCalls(NormalizerContextInterface::class);

        $fieldNormalizer = new ReferenceOneFieldNormalizer($identifierAccessor, $accessor);

        $data = $fieldNormalizer->normalizeField(
            'relation',
            $object,
            $context
        );

        self::assertSame('id1', $data);
    }
}
