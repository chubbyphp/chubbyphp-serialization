<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization\Unit\Normalizer;

use Chubbyphp\Mock\MockMethod\WithReturn;
use Chubbyphp\Mock\MockObjectBuilder;
use Chubbyphp\Serialization\Normalizer\CallbackLinkNormalizer;
use Chubbyphp\Serialization\Normalizer\NormalizerContextInterface;
use Chubbyphp\Serialization\SerializerLogicException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Link\LinkInterface;

/**
 * @covers \Chubbyphp\Serialization\Normalizer\CallbackLinkNormalizer
 *
 * @internal
 */
final class CallbackLinkNormalizerTest extends TestCase
{
    public function testNormalizeLink(): void
    {
        $object = new \stdClass();

        $builder = new MockObjectBuilder();

        /** @var LinkInterface|MockObject $link */
        $link = $builder->create(LinkInterface::class, [
            new WithReturn('getHref', [], '/api/model/id1'),
            new WithReturn('isTemplated', [], false),
            new WithReturn('getRels', [], ['model']),
            new WithReturn('getAttributes', [], ['method' => 'GET']),
        ]);

        /** @var MockObject|NormalizerContextInterface $normalizerContext */
        $normalizerContext = $builder->create(NormalizerContextInterface::class, []);

        $linkNormalizer = new CallbackLinkNormalizer(
            static fn (string $path, $object, NormalizerContextInterface $context) => $link
        );

        self::assertEquals(
            [
                'href' => '/api/model/id1',
                'templated' => false,
                'rel' => ['model'],
                'attributes' => ['method' => 'GET'],
            ],
            $linkNormalizer->normalizeLink('name', $object, $normalizerContext)
        );
    }

    public function testNormalizeLinkWithWrongDataType(): void
    {
        $this->expectException(SerializerLogicException::class);
        $this->expectExceptionMessage(
            'The link normalizer callback needs to return a Psr\Link\LinkInterface|null, "string" given at path: "name"'
        );

        $object = new \stdClass();

        $builder = new MockObjectBuilder();

        /** @var MockObject|NormalizerContextInterface $normalizerContext */
        $normalizerContext = $builder->create(NormalizerContextInterface::class, []);

        $linkNormalizer = new CallbackLinkNormalizer(
            static fn (string $path, $object, NormalizerContextInterface $context) => 'test'
        );

        $linkNormalizer->normalizeLink('name', $object, $normalizerContext);
    }
}
