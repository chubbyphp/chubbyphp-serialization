<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization\Unit\Policy;

use Chubbyphp\Mock\MockByCallsTrait;
use Chubbyphp\Serialization\Normalizer\NormalizerContextInterface;
use Chubbyphp\Serialization\Policy\NullPolicy;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Chubbyphp\Serialization\Policy\NullPolicy
 *
 * @internal
 */
final class NullPolicyTest extends TestCase
{
    use MockByCallsTrait;

    public function testIsCompliantReturnsTrue(): void
    {
        error_clear_last();

        $object = new \stdClass();

        /** @var NormalizerContextInterface|MockObject $context */
        $context = $this->getMockByCalls(NormalizerContextInterface::class);

        $policy = new NullPolicy();

        self::assertTrue($policy->isCompliant($context, $object));

        $error = error_get_last();

        self::assertNotNull($error);

        self::assertSame(E_USER_DEPRECATED, $error['type']);
        self::assertSame('Use "isCompliantIncludingPath()" instead of "isCompliant()"', $error['message']);
    }

    public function testIsComplianIncludingPathtReturnsTrue(): void
    {
        $object = new \stdClass();

        $path = '';

        /** @var NormalizerContextInterface|MockObject $context */
        $context = $this->getMockByCalls(NormalizerContextInterface::class);

        $policy = new NullPolicy();

        self::assertTrue($policy->isCompliantIncludingPath($path, $object, $context));
    }
}
