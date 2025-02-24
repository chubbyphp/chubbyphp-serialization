<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization\Unit\Normalizer;

use Chubbyphp\Mock\MockMethod\WithReturn;
use Chubbyphp\Mock\MockObjectBuilder;
use Chubbyphp\Serialization\Normalizer\LinkNormalizer;
use Chubbyphp\Serialization\Normalizer\NormalizerContextInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Link\LinkInterface;

/**
 * @covers \Chubbyphp\Serialization\Normalizer\LinkNormalizer
 *
 * @internal
 */
final class LinkNormalizerTest extends TestCase
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

        $linkNormalizer = new LinkNormalizer($link);

        self::assertEquals(
            [
                'href' => '/api/model/id1',
                'templated' => false,
                'rel' => [
                    'model',
                ],
                'attributes' => [
                    'method' => 'GET',
                ],
            ],
            $linkNormalizer->normalizeLink('name', $object, $normalizerContext)
        );
    }
}
