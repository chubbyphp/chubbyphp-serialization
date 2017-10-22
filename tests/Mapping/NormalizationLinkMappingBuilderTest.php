<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization\Mapping;

use Chubbyphp\Serialization\Normalizer\LinkNormalizerInterface;
use Chubbyphp\Serialization\Mapping\NormalizationLinkMappingBuilder;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Chubbyphp\Serialization\Mapping\NormalizationLinkMappingBuilder
 */
class NormalizationLinkMappingBuilderTest extends TestCase
{
    public function testGetDefaultMapping()
    {
        $normalizer = $this->getLinkNormalizer();

        $linkMapping = NormalizationLinkMappingBuilder::create('name', $normalizer)->getMapping();

        self::assertSame('name', $linkMapping->getName());
        self::assertSame([], $linkMapping->getGroups());
        self::assertSame($normalizer, $linkMapping->getLinkNormalizer());
    }

    public function testGetMapping()
    {
        $normalizer = $this->getLinkNormalizer();

        $linkMapping = NormalizationLinkMappingBuilder::create('name', $normalizer)
            ->setGroups(['group1'])
            ->getMapping();

        self::assertSame('name', $linkMapping->getName());
        self::assertSame(['group1'], $linkMapping->getGroups());
        self::assertSame($normalizer, $linkMapping->getLinkNormalizer());
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
