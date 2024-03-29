<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization\Unit\Policy;

use Chubbyphp\Mock\Call;
use Chubbyphp\Mock\MockByCallsTrait;
use Chubbyphp\Serialization\Normalizer\NormalizerContextInterface;
use Chubbyphp\Serialization\Policy\OrPolicy;
use Chubbyphp\Serialization\Policy\PolicyInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Chubbyphp\Serialization\Policy\OrPolicy
 *
 * @internal
 */
final class OrPolicyTest extends TestCase
{
    use MockByCallsTrait;

    public function testIsCompliantIncludingPathReturnsTrueIfOnePolicyIncludingPathReturnsTrue(): void
    {
        $object = new \stdClass();

        $path = '';

        /** @var MockObject|NormalizerContextInterface $context */
        $context = $this->getMockByCalls(NormalizerContextInterface::class, []);

        /** @var MockObject|PolicyInterface $nonCompliantPolicy */
        $nonCompliantPolicy = $this->getMockByCalls(PolicyInterface::class, [
            Call::create('isCompliant')->with($path, $object, $context)->willReturn(false),
        ]);

        /** @var MockObject|PolicyInterface $nonCompliantPcompliantPolicyolicy */
        $compliantPolicy = $this->getMockByCalls(PolicyInterface::class, [
            Call::create('isCompliant')->with($path, $object, $context)->willReturn(true),
        ]);

        /** @var MockObject|PolicyInterface $notToBeCalledPolicy */
        $notToBeCalledPolicy = $this->getMockByCalls(PolicyInterface::class, []);

        $policy = new OrPolicy([$nonCompliantPolicy, $compliantPolicy, $notToBeCalledPolicy]);

        self::assertTrue($policy->isCompliant($path, $object, $context));
    }

    public function testIsCompliantIncludingReturnsFalseIfAllPoliciesReturnFalse(): void
    {
        $object = new \stdClass();

        $path = '';

        /** @var MockObject|NormalizerContextInterface $context */
        $context = $this->getMockByCalls(NormalizerContextInterface::class, []);

        /** @var MockObject|PolicyInterface $nonCompliantPolicy1 */
        $nonCompliantPolicy1 = $this->getMockByCalls(PolicyInterface::class, [
            Call::create('isCompliant')->with($path, $object, $context)->willReturn(false),
        ]);

        /** @var MockObject|PolicyInterface $nonCompliantPolicy2 */
        $nonCompliantPolicy2 = $this->getMockByCalls(PolicyInterface::class, [
            Call::create('isCompliant')->with($path, $object, $context)->willReturn(false),
        ]);

        $policy = new OrPolicy([$nonCompliantPolicy1, $nonCompliantPolicy2]);

        self::assertFalse($policy->isCompliant($path, $object, $context));
    }
}
