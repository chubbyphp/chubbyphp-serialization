<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization\Unit\Policy;

use Chubbyphp\Mock\Call;
use Chubbyphp\Mock\MockByCallsTrait;
use Chubbyphp\Serialization\Normalizer\NormalizerContextInterface;
use Chubbyphp\Serialization\Policy\AndPolicy;
use Chubbyphp\Serialization\Policy\PolicyInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Chubbyphp\Serialization\Policy\AndPolicy
 *
 * @internal
 */
final class AndPolicyTest extends TestCase
{
    use MockByCallsTrait;

    public function testIsCompliantIncludingPathReturnsTrueWithMultipleCompliantPolicies(): void
    {
        $object = new \stdClass();

        $path = '';

        /** @var MockObject|NormalizerContextInterface $context */
        $context = $this->getMockByCalls(NormalizerContextInterface::class);

        /** @var MockObject|PolicyInterface $compliantPolicy1 */
        $compliantPolicy1 = $this->getMockByCalls(PolicyInterface::class, [
            Call::create('isCompliant')->with($path, $object, $context)->willReturn(true),
        ]);

        /** @var MockObject|PolicyInterface $compliantPolicy2 */
        $compliantPolicy2 = $this->getMockByCalls(PolicyInterface::class, [
            Call::create('isCompliant')->with($path, $object, $context)->willReturn(true),
        ]);

        $policy = new AndPolicy([$compliantPolicy1, $compliantPolicy2]);

        self::assertTrue($policy->isCompliant($path, $object, $context));
    }

    public function testIsCompliantIncludingPathReturnsFalseWithNonCompliantIncludingPathPolicy(): void
    {
        $object = new \stdClass();

        $path = '';

        /** @var MockObject|NormalizerContextInterface $context */
        $context = $this->getMockByCalls(NormalizerContextInterface::class);

        /** @var MockObject|PolicyInterface $compliantPolicy */
        $compliantPolicy = $this->getMockByCalls(PolicyInterface::class, [
            Call::create('isCompliant')->with($path, $object, $context)->willReturn(true),
        ]);

        /** @var MockObject|PolicyInterface $compliantPolicy2 */
        $nonCompliantPolicy = $this->getMockByCalls(PolicyInterface::class, [
            Call::create('isCompliant')->with($path, $object, $context)->willReturn(false),
        ]);

        /** @var MockObject|PolicyInterface $notExpectedToBeCalledPolicy */
        $notExpectedToBeCalledPolicy = $this->getMockByCalls(PolicyInterface::class);

        $policy = new AndPolicy([$compliantPolicy, $nonCompliantPolicy, $notExpectedToBeCalledPolicy]);

        self::assertFalse($policy->isCompliant($path, $object, $context));
    }
}
