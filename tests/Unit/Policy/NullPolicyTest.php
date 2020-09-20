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

    public function testIsCompliantIncludingReturnsTrue(): void
    {
        $object = new \stdClass();

        $path = '';

        /** @var NormalizerContextInterface|MockObject $context */
        $context = $this->getMockByCalls(NormalizerContextInterface::class);

        $policy = new NullPolicy();

        self::assertTrue($policy->isCompliant($path, $object, $context));
    }
}
