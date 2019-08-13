<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization\Policy;

use Chubbyphp\Mock\Call;
use Chubbyphp\Mock\MockByCallsTrait;
use Chubbyphp\Serialization\Normalizer\NormalizerContextInterface;
use Chubbyphp\Serialization\Policy\OrPolicy;
use Chubbyphp\Serialization\Policy\PolicyInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Chubbyphp\Serialization\Policy\OrPolicy
 */
class OrPolicyTest extends TestCase
{
    use MockByCallsTrait;

    public function testIsCompliantReturnsTrueIfOnePolicyReturnsTrue()
    {
        $object = new \stdClass();

        /** @var NormalizerContextInterface|MockObject $context */
        $context = $this->getMockByCalls(NormalizerContextInterface::class, []);

        /** @var PolicyInterface|MockObject $nonCompliantPolicy */
        $nonCompliantPolicy = $this->getMockByCalls(PolicyInterface::class, [
            Call::create('isCompliant')->with($context, $object)->willReturn(false),
        ]);

        /** @var PolicyInterface|MockObject $compliantPolicy */
        $compliantPolicy = $this->getMockByCalls(PolicyInterface::class, [
            Call::create('isCompliant')->with($context, $object)->willReturn(true),
        ]);

        /** @var PolicyInterface|MockObject $notToBeCalledPolicy */
        $notToBeCalledPolicy = $this->getMockByCalls(PolicyInterface::class, []);

        $policy = new OrPolicy([$nonCompliantPolicy, $compliantPolicy, $notToBeCalledPolicy]);

        self::assertTrue($policy->isCompliant($context, $object));
    }

    public function testIsCompliantReturnsFalseIfAllPoliciesReturnFalse()
    {
        $object = new \stdClass();

        /** @var NormalizerContextInterface|MockObject $context */
        $context = $this->getMockByCalls(NormalizerContextInterface::class, []);

        /** @var PolicyInterface|MockObject $nonCompliantPolicy1 */
        $nonCompliantPolicy1 = $this->getMockByCalls(PolicyInterface::class, [
            Call::create('isCompliant')->with($context, $object)->willReturn(false),
        ]);

        /** @var PolicyInterface|MockObject $nonCompliantPolicy2 */
        $nonCompliantPolicy2 = $this->getMockByCalls(PolicyInterface::class, [
            Call::create('isCompliant')->with($context, $object)->willReturn(false),
        ]);

        $policy = new OrPolicy([$nonCompliantPolicy1, $nonCompliantPolicy2]);

        self::assertFalse($policy->isCompliant($context, $object));
    }
}
