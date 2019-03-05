<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization\Normalizer;

use Chubbyphp\Mock\Call;
use Chubbyphp\Mock\MockByCallsTrait;
use Chubbyphp\Serialization\Normalizer\CallbackLinkNormalizer;
use Chubbyphp\Serialization\Normalizer\NormalizerContextInterface;
use Chubbyphp\Serialization\SerializerLogicException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Link\LinkInterface;

/**
 * @covers \Chubbyphp\Serialization\Normalizer\CallbackLinkNormalizer
 */
class CallbackLinkNormalizerTest extends TestCase
{
    use MockByCallsTrait;

    public function testNormalizeLink()
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

        $linkNormalizer = new CallbackLinkNormalizer(
            function (
                string $path,
                $object,
                NormalizerContextInterface $context
            ) use ($link) {
                return $link;
            }
        );

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

    public function testNormalizeLinkWithNull()
    {
        $object = new \stdClass();

        /** @var NormalizerContextInterface|MockObject $normalizerContext */
        $normalizerContext = $this->getMockByCalls(NormalizerContextInterface::class);

        $linkNormalizer = new CallbackLinkNormalizer(
            function (
                string $path,
                $object,
                NormalizerContextInterface $context
            ) {
            }
        );

        self::assertNull(
            $linkNormalizer->normalizeLink('name', $object, $normalizerContext)
        );
    }

    public function testNormalizeLinkWithWrongDataType()
    {
        $this->expectException(SerializerLogicException::class);
        $this->expectExceptionMessage(
            'The link normalizer callback needs to return a Psr\Link\LinkInterface|null, "string" given at path: "name"'
        );

        $object = new \stdClass();

        /** @var NormalizerContextInterface|MockObject $normalizerContext */
        $normalizerContext = $this->getMockByCalls(NormalizerContextInterface::class);

        $linkNormalizer = new CallbackLinkNormalizer(
            function (
                string $path,
                $object,
                NormalizerContextInterface $context
            ) {
                return 'test';
            }
        );

        $linkNormalizer->normalizeLink('name', $object, $normalizerContext);
    }
}
