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
    use PolicyIncludingPathTrait;

    public function testIsCompliantReturnsTrueWithMultipleCompliantPolicies(): void
    {
        error_clear_last();

        $object = new \stdClass();

        /** @var NormalizerContextInterface|MockObject $context */
        $context = $this->getMockByCalls(NormalizerContextInterface::class, []);

        /** @var PolicyInterface|MockObject $compliantPolicy1 */
        $compliantPolicy1 = $this->getMockByCalls(PolicyInterface::class, [
            Call::create('isCompliant')->with($context, $object)->willReturn(true),
        ]);

        /** @var PolicyInterface|MockObject $compliantPolicy2 */
        $compliantPolicy2 = $this->getMockByCalls(PolicyInterface::class, [
            Call::create('isCompliant')->with($context, $object)->willReturn(true),
        ]);

        $policy = new AndPolicy([$compliantPolicy1, $compliantPolicy2]);

        self::assertTrue($policy->isCompliant($context, $object));

        $error = error_get_last();

        self::assertNotNull($error);

        self::assertSame(E_USER_DEPRECATED, $error['type']);
        self::assertSame(
            sprintf('The %s::isCompliant method is deprecated ().', PolicyInterface::class),
            $error['message']
        );
    }

    public function testIsCompliantReturnsFalseWithNonCompliantPolicy(): void
    {
        error_clear_last();

        $object = new \stdClass();

        /** @var NormalizerContextInterface|MockObject $context */
        $context = $this->getMockByCalls(NormalizerContextInterface::class, []);

        /** @var PolicyInterface|MockObject $compliantPolicy1 */
        $compliantPolicy1 = $this->getMockByCalls(PolicyInterface::class, [
            Call::create('isCompliant')->with($context, $object)->willReturn(true),
        ]);

        /** @var PolicyInterface|MockObject $nonCompliantPolicy */
        $nonCompliantPolicy = $this->getMockByCalls(PolicyInterface::class, [
            Call::create('isCompliant')->with($context, $object)->willReturn(false),
        ]);

        /** @var PolicyInterface|MockObject $notExpectedToBeCalledPolicy */
        $notExpectedToBeCalledPolicy = $this->getMockByCalls(PolicyInterface::class);

        $policy = new AndPolicy([$compliantPolicy1, $nonCompliantPolicy, $notExpectedToBeCalledPolicy]);

        self::assertFalse($policy->isCompliant($context, $object));

        $error = error_get_last();

        self::assertNotNull($error);

        self::assertSame(E_USER_DEPRECATED, $error['type']);
        self::assertSame(
            sprintf('The %s::isCompliant method is deprecated ().', PolicyInterface::class),
            $error['message']
        );
    }

    public function testIsCompliantIncludingPathReturnsTrueWithMultipleCompliantPolicies(): void
    {
        error_clear_last();

        $object = new \stdClass();

        $path = '';

        /** @var NormalizerContextInterface|MockObject $context */
        $context = $this->getMockByCalls(NormalizerContextInterface::class, []);

        /** @var PolicyInterface|MockObject $compliantPolicy1 */
        $compliantPolicy1 = $this->getCompliantPolicyIncludingPath(true);

        /** @var PolicyInterface|MockObject $compliantPolicy2 */
        $compliantPolicy2 = $this->getCompliantPolicyIncludingPath(true);

        $policy = new AndPolicy([$compliantPolicy1, $compliantPolicy2]);

        self::assertTrue($policy->isCompliantIncludingPath($path, $object, $context));

        $error = error_get_last();

        self::assertNull($error);
    }

    public function testIsCompliantIncludingPathReturnsFalseWithNonCompliantIncludingPathPolicy(): void
    {
        error_clear_last();

        $object = new \stdClass();

        $path = '';

        /** @var NormalizerContextInterface|MockObject $context */
        $context = $this->getMockByCalls(NormalizerContextInterface::class, []);

        /** @var PolicyInterface|MockObject $compliantPolicy1 */
        $compliantPolicy1 = $this->getCompliantPolicyIncludingPath(true);

        /** @var PolicyInterface|MockObject $nonCompliantPolicy */
        $nonCompliantPolicy = $this->getCompliantPolicyIncludingPath(false);

        /** @var PolicyInterface|MockObject $notExpectedToBeCalledPolicy */
        $notExpectedToBeCalledPolicy = $this->getMockByCalls(PolicyInterface::class);

        $policy = new AndPolicy([$compliantPolicy1, $nonCompliantPolicy, $notExpectedToBeCalledPolicy]);

        self::assertFalse($policy->isCompliantIncludingPath($path, $object, $context));

        $error = error_get_last();

        self::assertNull($error);
    }

    public function testIsCompliantIncludingPathReturnsFalseWithNonCompliantPolicy(): void
    {
        error_clear_last();

        $object = new \stdClass();

        $path = '';

        /** @var NormalizerContextInterface|MockObject $context */
        $context = $this->getMockByCalls(NormalizerContextInterface::class, []);

        /** @var PolicyInterface|MockObject $compliantPolicy1 */
        $compliantPolicy1 = $this->getCompliantPolicyIncludingPath(true);

        /** @var PolicyInterface|MockObject $nonCompliantPolicy */
        $nonCompliantPolicy = $this->getMockByCalls(PolicyInterface::class, [
            Call::create('isCompliant')->with($context, $object)->willReturn(false),
        ]);

        /** @var PolicyInterface|MockObject $notExpectedToBeCalledPolicy */
        $notExpectedToBeCalledPolicy = $this->getMockByCalls(PolicyInterface::class);

        $policy = new AndPolicy([$compliantPolicy1, $nonCompliantPolicy, $notExpectedToBeCalledPolicy]);

        self::assertFalse($policy->isCompliantIncludingPath($path, $object, $context));

        $error = error_get_last();

        self::assertNotNull($error);

        self::assertSame(E_USER_DEPRECATED, $error['type']);
        self::assertSame(
            sprintf('The %s::isCompliant method is deprecated ().', PolicyInterface::class),
            $error['message']
        );
    }
}
