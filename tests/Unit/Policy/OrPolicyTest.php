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
    use PolicyIncludingPathTrait;

    public function testIsCompliantReturnsTrueIfOnePolicyReturnsTrue(): void
    {
        error_clear_last();

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

        $error = error_get_last();

        self::assertNotNull($error);

        self::assertSame(E_USER_DEPRECATED, $error['type']);
        self::assertSame(
            sprintf('The %s::isCompliant method is deprecated ().', PolicyInterface::class),
            $error['message']
        );
    }

    public function testIsCompliantReturnsFalseIfAllPoliciesReturnFalse(): void
    {
        error_clear_last();

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

        $error = error_get_last();

        self::assertNotNull($error);

        self::assertSame(E_USER_DEPRECATED, $error['type']);
        self::assertSame(
            sprintf('The %s::isCompliant method is deprecated ().', PolicyInterface::class),
            $error['message']
        );
    }

    public function testIsCompliantIncludingPathReturnsTrueIfOnePolicyIncludingPathReturnsTrue(): void
    {
        error_clear_last();

        $object = new \stdClass();

        $path = '';

        /** @var NormalizerContextInterface|MockObject $context */
        $context = $this->getMockByCalls(NormalizerContextInterface::class, []);

        /** @var PolicyInterface|MockObject $nonCompliantPolicy */
        $nonCompliantPolicy = $this->getCompliantPolicyIncludingPath(false);

        /** @var PolicyInterface|MockObject $compliantPolicy */
        $compliantPolicy = $this->getCompliantPolicyIncludingPath(true);

        /** @var PolicyInterface|MockObject $notToBeCalledPolicy */
        $notToBeCalledPolicy = $this->getMockByCalls(PolicyInterface::class, []);

        $policy = new OrPolicy([$nonCompliantPolicy, $compliantPolicy, $notToBeCalledPolicy]);

        self::assertTrue($policy->isCompliantIncludingPath($path, $object, $context));

        $error = error_get_last();

        self::assertNull($error);
    }

    public function testIsCompliantIncludingPathReturnsTrueIfOnePolicyReturnsTrue(): void
    {
        error_clear_last();

        $object = new \stdClass();

        $path = '';

        /** @var NormalizerContextInterface|MockObject $context */
        $context = $this->getMockByCalls(NormalizerContextInterface::class, []);

        /** @var PolicyInterface|MockObject $nonCompliantPolicy */
        $nonCompliantPolicy = $this->getCompliantPolicyIncludingPath(false);

        /** @var PolicyInterface|MockObject $compliantPolicy */
        $compliantPolicy = $this->getMockByCalls(PolicyInterface::class, [
            Call::create('isCompliant')->with($context, $object)->willReturn(true),
        ]);

        /** @var PolicyInterface|MockObject $notToBeCalledPolicy */
        $notToBeCalledPolicy = $this->getMockByCalls(PolicyInterface::class, []);

        $policy = new OrPolicy([$nonCompliantPolicy, $compliantPolicy, $notToBeCalledPolicy]);

        self::assertTrue($policy->isCompliantIncludingPath($path, $object, $context));

        $error = error_get_last();

        self::assertNotNull($error);

        self::assertSame(E_USER_DEPRECATED, $error['type']);
        self::assertSame(
            sprintf('The %s::isCompliant method is deprecated ().', PolicyInterface::class),
            $error['message']
        );
    }

    public function testIsCompliantIncludingPathReturnsFalseIfAllPoliciesReturnFalse(): void
    {
        error_clear_last();

        $object = new \stdClass();

        $path = '';

        /** @var NormalizerContextInterface|MockObject $context */
        $context = $this->getMockByCalls(NormalizerContextInterface::class, []);

        /** @var PolicyInterface|MockObject $nonCompliantPolicy1 */
        $nonCompliantPolicy1 = $this->getCompliantPolicyIncludingPath(false);

        /** @var PolicyInterface|MockObject $nonCompliantPolicy2 */
        $nonCompliantPolicy2 = $this->getCompliantPolicyIncludingPath(false);

        $policy = new OrPolicy([$nonCompliantPolicy1, $nonCompliantPolicy2]);

        self::assertFalse($policy->isCompliantIncludingPath($path, $object, $context));

        $error = error_get_last();

        self::assertNull($error);
    }
}
