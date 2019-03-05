<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization\Mapping;

use Chubbyphp\Mock\MockByCallsTrait;
use Chubbyphp\Serialization\Mapping\NormalizationLinkMappingBuilder;
use Chubbyphp\Serialization\Normalizer\LinkNormalizerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Chubbyphp\Serialization\Mapping\NormalizationLinkMappingBuilder
 */
class NormalizationLinkMappingBuilderTest extends TestCase
{
    use MockByCallsTrait;

    public function testGetDefaultMapping()
    {
        /** @var LinkNormalizerInterface|MockObject $linkNormalizer */
        $linkNormalizer = $this->getMockByCalls(LinkNormalizerInterface::class);

        $linkMapping = NormalizationLinkMappingBuilder::create('name', $linkNormalizer)->getMapping();

        self::assertSame('name', $linkMapping->getName());
        self::assertSame([], $linkMapping->getGroups());
        self::assertSame($linkNormalizer, $linkMapping->getLinkNormalizer());
    }

    public function testGetMapping()
    {
        /** @var LinkNormalizerInterface|MockObject $linkNormalizer */
        $linkNormalizer = $this->getMockByCalls(LinkNormalizerInterface::class);

        $linkMapping = NormalizationLinkMappingBuilder::create('name', $linkNormalizer)
            ->setGroups(['group1'])
            ->getMapping();

        self::assertSame('name', $linkMapping->getName());
        self::assertSame(['group1'], $linkMapping->getGroups());
        self::assertSame($linkNormalizer, $linkMapping->getLinkNormalizer());
    }
}
