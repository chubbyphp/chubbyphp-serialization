<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization\Mapping;

use Chubbyphp\Serialization\Normalizer\LinkNormalizerInterface;
use Chubbyphp\Serialization\Mapping\NormalizationLinkMapping;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Chubbyphp\Serialization\Mapping\NormalizationLinkMapping
 */
class NormalizationLinkMappingTest extends TestCase
{
    public function testGetName()
    {
        $linkMapping = new NormalizationLinkMapping('name', ['group1'], $this->getLinkNormalizer());

        self::assertSame('name', $linkMapping->getName());
    }

    public function testGetGroups()
    {
        $linkMapping = new NormalizationLinkMapping('name', ['group1'], $this->getLinkNormalizer());

        self::assertSame(['group1'], $linkMapping->getGroups());
    }

    public function testGetLinkNormalizer()
    {
        $linkNormalizer = $this->getLinkNormalizer();

        $linkMapping = new NormalizationLinkMapping('name', ['group1'], $linkNormalizer);

        self::assertSame($linkNormalizer, $linkMapping->getLinkNormalizer());
    }

    /**
     * @return LinkNormalizerInterface
     */
    private function getLinkNormalizer(): LinkNormalizerInterface
    {
        /** @var LinkNormalizerInterface|\PHPUnit_Framework_MockObject_MockObject $linkNormalizer */
        $linkNormalizer = $this->getMockBuilder(LinkNormalizerInterface::class)->getMockForAbstractClass();

        return $linkNormalizer;
    }
}
