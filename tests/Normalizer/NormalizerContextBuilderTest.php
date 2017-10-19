<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization\Normalizer;

use Chubbyphp\Serialization\Normalizer\NormalizerContextBuilder;
use Chubbyphp\Serialization\Normalizer\NormalizerContextInterface;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Chubbyphp\Serialization\Normalizer\NormalizerContextBuilder
 */
class NormalizerContextBuilderTest extends TestCase
{
    public function testCreate()
    {
        $context = NormalizerContextBuilder::create()->getContext();

        self::assertInstanceOf(NormalizerContextInterface::class, $context);

        self::assertSame([], $context->getGroups());
    }

    public function testCreateWithOverridenSettings()
    {
        $context = NormalizerContextBuilder::create()
            ->setGroups(['group1'])
            ->getContext();

        self::assertInstanceOf(NormalizerContextInterface::class, $context);

        self::assertSame(['group1'], $context->getGroups());
    }
}
