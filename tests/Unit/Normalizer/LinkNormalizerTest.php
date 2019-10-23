<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization\Unit\Normalizer;

use Chubbyphp\Mock\Call;
use Chubbyphp\Mock\MockByCallsTrait;
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
    use MockByCallsTrait;

    public function testNormalizeLink(): void
    {
        $object = new \stdClass();

        /** @var LinkInterface|MockObject $link */
        $link = $this->getMockByCalls(LinkInterface::class, [
            Call::create('getHref')->with()->willReturn('/api/model/id1'),
            Call::create('isTemplated')->with()->willReturn(false),
            Call::create('getRels')->with()->willReturn(['model']),
            Call::create('getAttributes')->with()->willReturn(['method' => 'GET']),
        ]);

        /** @var NormalizerContextInterface|MockObject $normalizerContext */
        $normalizerContext = $this->getMockByCalls(NormalizerContextInterface::class);

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
