<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization\Mapping;

use Chubbyphp\Mock\MockByCallsTrait;
use Chubbyphp\Serialization\Mapping\NormalizationLinkMapping;
use Chubbyphp\Serialization\Normalizer\LinkNormalizerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Chubbyphp\Serialization\Mapping\NormalizationLinkMapping
 */
class NormalizationLinkMappingTest extends TestCase
{
    use MockByCallsTrait;

    public function testGetName()
    {
        /** @var LinkNormalizerInterface|MockObject $linkNormalizer */
        $linkNormalizer = $this->getMockByCalls(LinkNormalizerInterface::class);

        $linkMapping = new NormalizationLinkMapping('name', ['group1'], $linkNormalizer);

        self::assertSame('name', $linkMapping->getName());
    }

    public function testGetGroups()
    {
        /** @var LinkNormalizerInterface|MockObject $linkNormalizer */
        $linkNormalizer = $this->getMockByCalls(LinkNormalizerInterface::class);

        $linkMapping = new NormalizationLinkMapping('name', ['group1'], $linkNormalizer);

        self::assertSame(['group1'], $linkMapping->getGroups());
    }

    public function testGetLinkNormalizer()
    {
        /** @var LinkNormalizerInterface|MockObject $linkNormalizer */
        $linkNormalizer = $this->getMockByCalls(LinkNormalizerInterface::class);

        $linkMapping = new NormalizationLinkMapping('name', ['group1'], $linkNormalizer);

        self::assertSame($linkNormalizer, $linkMapping->getLinkNormalizer());
    }
}
