<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization\Unit\Mapping;

use Chubbyphp\Mock\MockObjectBuilder;
use Chubbyphp\Serialization\Mapping\NormalizationLinkMapping;
use Chubbyphp\Serialization\Normalizer\LinkNormalizerInterface;
use Chubbyphp\Serialization\Policy\PolicyInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Chubbyphp\Serialization\Mapping\NormalizationLinkMapping
 *
 * @internal
 */
final class NormalizationLinkMappingTest extends TestCase
{
    public function testGetName(): void
    {
        $builder = new MockObjectBuilder();

        /** @var LinkNormalizerInterface|MockObject $linkNormalizer */
        $linkNormalizer = $builder->create(LinkNormalizerInterface::class, []);

        $linkMapping = new NormalizationLinkMapping('name', $linkNormalizer);

        self::assertSame('name', $linkMapping->getName());
    }

    public function testGetLinkNormalizer(): void
    {
        $builder = new MockObjectBuilder();

        /** @var LinkNormalizerInterface|MockObject $linkNormalizer */
        $linkNormalizer = $builder->create(LinkNormalizerInterface::class, []);

        $linkMapping = new NormalizationLinkMapping('name', $linkNormalizer);

        self::assertSame($linkNormalizer, $linkMapping->getLinkNormalizer());
    }

    public function testGetPolicy(): void
    {
        $builder = new MockObjectBuilder();

        /** @var LinkNormalizerInterface|MockObject $linkNormalizer */
        $linkNormalizer = $builder->create(LinkNormalizerInterface::class, []);

        /** @var MockObject|PolicyInterface $policy */
        $policy = $builder->create(PolicyInterface::class, []);

        $linkMapping = new NormalizationLinkMapping('name', $linkNormalizer, $policy);

        self::assertSame($policy, $linkMapping->getPolicy());
    }
}
