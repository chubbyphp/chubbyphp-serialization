<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization\Unit;

use Chubbyphp\DecodeEncode\Encoder\EncoderInterface;
use Chubbyphp\Mock\MockMethod\WithReturn;
use Chubbyphp\Mock\MockObjectBuilder;
use Chubbyphp\Serialization\Normalizer\NormalizerContextInterface;
use Chubbyphp\Serialization\Normalizer\NormalizerInterface;
use Chubbyphp\Serialization\Serializer;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Chubbyphp\Serialization\Serializer
 *
 * @internal
 */
final class SerializerTest extends TestCase
{
    public function testSerialize(): void
    {
        $object = new \stdClass();
        $object->name = 'Name';

        $builder = new MockObjectBuilder();

        /** @var NormalizerContextInterface $context */
        $context = $builder->create(NormalizerContextInterface::class, []);

        /** @var NormalizerInterface $normalizer */
        $normalizer = $builder->create(NormalizerInterface::class, [
            new WithReturn('normalize', [$object, $context, 'path'], ['name' => 'Name']),
        ]);

        /** @var EncoderInterface $encoder */
        $encoder = $builder->create(EncoderInterface::class, [
            new WithReturn('encode', [['name' => 'Name'], 'application/json'], '{"name":"Name"}'),
        ]);

        $serializer = new Serializer($normalizer, $encoder);

        $data = $serializer->serialize($object, 'application/json', $context, 'path');

        self::assertSame('{"name":"Name"}', $data);
    }

    public function testNormalize(): void
    {
        $object = new \stdClass();
        $object->name = 'Name';

        $builder = new MockObjectBuilder();

        /** @var NormalizerContextInterface $context */
        $context = $builder->create(NormalizerContextInterface::class, []);

        /** @var NormalizerInterface $normalizer */
        $normalizer = $builder->create(NormalizerInterface::class, [
            new WithReturn('normalize', [$object, $context, 'path'], ['name' => 'Name']),
        ]);

        /** @var EncoderInterface $encoder */
        $encoder = $builder->create(EncoderInterface::class, []);

        $serializer = new Serializer($normalizer, $encoder);

        $data = $serializer->normalize($object, $context, 'path');

        self::assertSame(['name' => 'Name'], $data);
    }

    public function testGetContentTypes(): void
    {
        $builder = new MockObjectBuilder();

        /** @var NormalizerInterface $normalizer */
        $normalizer = $builder->create(NormalizerInterface::class, []);

        /** @var EncoderInterface $encoder */
        $encoder = $builder->create(EncoderInterface::class, [
            new WithReturn('getContentTypes', [], ['application/json']),
        ]);

        $serializer = new Serializer($normalizer, $encoder);

        self::assertSame(['application/json'], $serializer->getContentTypes());
    }

    public function testEncode(): void
    {
        $builder = new MockObjectBuilder();

        /** @var NormalizerInterface $normalizer */
        $normalizer = $builder->create(NormalizerInterface::class, []);

        /** @var EncoderInterface $encoder */
        $encoder = $builder->create(EncoderInterface::class, [
            new WithReturn('encode', [['name' => 'Name'], 'application/json'], '{"name":"Name"}'),
        ]);

        $serializer = new Serializer($normalizer, $encoder);

        self::assertSame('{"name":"Name"}', $serializer->encode(['name' => 'Name'], 'application/json'));
    }
}
