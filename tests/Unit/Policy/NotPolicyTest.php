<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization\Unit\Policy;

use Chubbyphp\Mock\Call;
use Chubbyphp\Mock\MockByCallsTrait;
use Chubbyphp\Serialization\Normalizer\NormalizerContextInterface;
use Chubbyphp\Serialization\Policy\NotPolicy;
use Chubbyphp\Serialization\Policy\PolicyInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Chubbyphp\Serialization\Policy\NotPolicy
 *
 * @internal
 */
final class NotPolicyTest extends TestCase
{
    use MockByCallsTrait;

    public function testIsCompliantIncludingPathReturnsTrueIfGivenPolicyIncludingPathReturnsFalse(): void
    {
        $object = new \stdClass();

        $path = '';

        /** @var MockObject|NormalizerContextInterface $context */
        $context = $this->getMockByCalls(NormalizerContextInterface::class, []);

        /** @var MockObject|PolicyInterface $nonCompliantPolicy */
        $nonCompliantPolicy = $this->getMockByCalls(PolicyInterface::class, [
            Call::create('isCompliant')->with($path, $object, $context)->willReturn(false),
        ]);

        $policy = new NotPolicy($nonCompliantPolicy);

        self::assertTrue($policy->isCompliant($path, $object, $context));
    }

    public function testIsCompliantIncludingPathReturnsFalseIfGivenPolicyIncludingPathReturnsTrue(): void
    {
        $object = new \stdClass();

        $path = '';

        /** @var MockObject|NormalizerContextInterface $context */
        $context = $this->getMockByCalls(NormalizerContextInterface::class, []);

        /** @var MockObject|PolicyInterface $nonCompliantPolicy */
        $nonCompliantPolicy = $this->getMockByCalls(PolicyInterface::class, [
            Call::create('isCompliant')->with($path, $object, $context)->willReturn(true),
        ]);

        $policy = new NotPolicy($nonCompliantPolicy);

        self::assertFalse($policy->isCompliant($path, $object, $context));
    }
}
