<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization\Normalizer;

use Chubbyphp\Mock\Call;
use Chubbyphp\Mock\MockByCallsTrait;
use Chubbyphp\Serialization\Accessor\AccessorInterface;
use Chubbyphp\Serialization\Normalizer\NormalizerContextInterface;
use Chubbyphp\Serialization\Normalizer\Relation\ReferenceOneFieldNormalizer;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Chubbyphp\Serialization\Normalizer\Relation\ReferenceOneFieldNormalizer
 */
class ReferenceOneFieldNormalizerTest extends TestCase
{
    use MockByCallsTrait;

    public function testNormalizeFieldWithNull()
    {
        $object = new \stdClass;

        /** @var AccessorInterface|MockObject $identifierAccessor */
        $identifierAccessor = $this->getMockByCalls(AccessorInterface::class);

        /** @var AccessorInterface|MockObject $accessor */
        $accessor = $this->getMockByCalls(AccessorInterface::class, [
            Call::create('getValue')->with($object)->willReturn(null),
        ]);

        /** @var NormalizerContextInterface|MockObject $context */
        $context = $this->getMockByCalls(NormalizerContextInterface::class);

        $fieldNormalizer = new ReferenceOneFieldNormalizer($identifierAccessor, $accessor);

        $data = $fieldNormalizer->normalizeField(
            'relation',
            $object,
            $context
        );

        self::assertSame(null, $data);
    }

    public function testNormalizeFieldWithObject()
    {
        $relation = new \stdClass;

        $object = new \stdClass;

        /** @var AccessorInterface|MockObject $identifierAccessor */
        $identifierAccessor = $this->getMockByCalls(AccessorInterface::class, [
            Call::create('getValue')->with($relation)->willReturn('id1'),
        ]);

        /** @var AccessorInterface|MockObject $accessor */
        $accessor = $this->getMockByCalls(AccessorInterface::class, [
            Call::create('getValue')->with($object)->willReturn($relation),
        ]);

        /** @var NormalizerContextInterface|MockObject $context */
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
