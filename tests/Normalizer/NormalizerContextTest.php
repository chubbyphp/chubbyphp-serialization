<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization\Normalizer;

use Chubbyphp\Serialization\Normalizer\NormalizerContext;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Chubbyphp\Serialization\Normalizer\NormalizerContext
 */
class NormalizerContextTest extends TestCase
{
    public function testCreate()
    {
        $context = new NormalizerContext();

        self::assertSame([], $context->getGroups());
    }

    public function testCreateWithOverridenSettings()
    {
        $context = new NormalizerContext(['group1']);

        self::assertSame(['group1'], $context->getGroups());
    }
}
