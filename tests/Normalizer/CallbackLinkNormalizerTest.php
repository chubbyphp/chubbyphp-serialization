<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization\Normalizer;

use Chubbyphp\Serialization\Normalizer\NormalizerContextInterface;
use Chubbyphp\Serialization\Normalizer\CallbackLinkNormalizer;
use PHPUnit\Framework\TestCase;
use Psr\Link\LinkInterface;

/**
 * @covers \Chubbyphp\Serialization\Normalizer\CallbackLinkNormalizer
 */
class CallbackLinkNormalizerTest extends TestCase
{
    public function testNormalizeLink()
    {
        $object = new class() {
            /**
             * @var string
             */
            private $id = 'id1';

            /**
             * @return string
             */
            public function getId(): string
            {
                return $this->id;
            }
        };

        $linkNormalizer = new CallbackLinkNormalizer(
            function (
                string $path,
                $object,
                NormalizerContextInterface $context
            ) {
                return $this->getLink('/api/model/'.$object->getId(), ['model'], ['method' => 'GET']);
            }
        );

        self::assertEquals(
            [
                'href' => '/api/model/id1',
                'templated' => false,
                'rel' => [
                    0 => 'model',
                ],
                'attributes' => [
                    'method' => 'GET',
                ],
            ],
            $linkNormalizer->normalizeLink('name', $object, $this->getNormalizerContext())
        );
    }

    public function testNormalizeLinkWithNull()
    {
        $object = new class() {
        };

        $linkNormalizer = new CallbackLinkNormalizer(
            function (
                string $path,
                $object,
                NormalizerContextInterface $context
            ) {
            }
        );

        self::assertNull(
            $linkNormalizer->normalizeLink('name', $object, $this->getNormalizerContext())
        );
    }

    /**
     * @return NormalizerContextInterface
     */
    private function getNormalizerContext(): NormalizerContextInterface
    {
        /** @var NormalizerContextInterface|\PHPUnit_Framework_MockObject_MockObject $context */
        $context = $this->getMockBuilder(NormalizerContextInterface::class)->getMockForAbstractClass();

        return $context;
    }

    /**
     * @param string $href
     * @param array  $rels
     * @param array  $attributes
     *
     * @return LinkInterface
     */
    private function getLink(string $href, array $rels, array $attributes): LinkInterface
    {
        /** @var LinkInterface|\PHPUnit_Framework_MockObject_MockObject $link */
        $link = $this->getMockBuilder(LinkInterface::class)->getMockForAbstractClass();

        $link->expects(self::any())->method('getHref')->willReturn($href);
        $link->expects(self::any())->method('isTemplated')->willReturnCallback(function () use ($href) {
            return false !== strpos($href, '{');
        });
        $link->expects(self::any())->method('getRels')->willReturn($rels);
        $link->expects(self::any())->method('getAttributes')->willReturn($attributes);

        return $link;
    }
}
