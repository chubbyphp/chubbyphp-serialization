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
    use PolicyIncludingPathTrait;

    public function testIsCompliantReturnsTrueIfGivenPolicyReturnsFalse(): void
    {
        $object = new \stdClass();

        /** @var NormalizerContextInterface|MockObject $context */
        $context = $this->getMockByCalls(NormalizerContextInterface::class, []);

        /** @var PolicyInterface|MockObject $nonCompliantPolicy */
        $nonCompliantPolicy = $this->getMockByCalls(PolicyInterface::class, [
            Call::create('isCompliant')->with($context, $object)->willReturn(false),
        ]);

        $policy = new NotPolicy($nonCompliantPolicy);

        self::assertTrue($policy->isCompliant($context, $object));
    }

    public function testIsCompliantReturnsFalseIfGivenPolicyReturnsTrue(): void
    {
        $object = new \stdClass();

        /** @var NormalizerContextInterface|MockObject $context */
        $context = $this->getMockByCalls(NormalizerContextInterface::class, []);

        /** @var PolicyInterface|MockObject $nonCompliantPolicy */
        $nonCompliantPolicy = $this->getMockByCalls(PolicyInterface::class, [
            Call::create('isCompliant')->with($context, $object)->willReturn(true),
        ]);

        $policy = new NotPolicy($nonCompliantPolicy);

        self::assertFalse($policy->isCompliant($context, $object));
    }

    public function testIsCompliantIncludingPathReturnsTrueIfGivenPolicyIncludingPathReturnsFalse(): void
    {
        $object = new \stdClass();

        $path = '';

        /** @var NormalizerContextInterface|MockObject $context */
        $context = $this->getMockByCalls(NormalizerContextInterface::class, []);

        /** @var PolicyInterface|MockObject $nonCompliantPolicy */
        $nonCompliantPolicy = $this->getCompliantPolicyIncludingPath(false);

        $policy = new NotPolicy($nonCompliantPolicy);

        self::assertTrue($policy->isCompliantIncludingPath($object, $context, $path));
    }

    public function testIsCompliantIncludingPathReturnsFalseIfGivenPolicyIncludingPathReturnsTrue(): void
    {
        $object = new \stdClass();

        $path = '';

        /** @var NormalizerContextInterface|MockObject $context */
        $context = $this->getMockByCalls(NormalizerContextInterface::class, []);

        /** @var PolicyInterface|MockObject $nonCompliantPolicy */
        $nonCompliantPolicy = $this->getCompliantPolicyIncludingPath(true);

        $policy = new NotPolicy($nonCompliantPolicy);

        self::assertFalse($policy->isCompliantIncludingPath($object, $context, $path));
    }

    public function testIsCompliantIncludingPathReturnsTrueIfGivenPolicyReturnsFalse(): void
    {
        $object = new \stdClass();

        $path = '';

        /** @var NormalizerContextInterface|MockObject $context */
        $context = $this->getMockByCalls(NormalizerContextInterface::class, []);

        /** @var PolicyInterface|MockObject $nonCompliantPolicy */
        $nonCompliantPolicy = $this->getMockByCalls(PolicyInterface::class, [
            Call::create('isCompliant')->with($context, $object)->willReturn(false),
        ]);

        $policy = new NotPolicy($nonCompliantPolicy);

        self::assertTrue($policy->isCompliantIncludingPath($object, $context, $path));
    }

    public function testIsCompliantIncludingPathReturnsFalseIfGivenPolicyReturnsTrue(): void
    {
        $object = new \stdClass();

        $path = '';

        /** @var NormalizerContextInterface|MockObject $context */
        $context = $this->getMockByCalls(NormalizerContextInterface::class, []);

        /** @var PolicyInterface|MockObject $nonCompliantPolicy */
        $nonCompliantPolicy = $this->getMockByCalls(PolicyInterface::class, [
            Call::create('isCompliant')->with($context, $object)->willReturn(true),
        ]);

        $policy = new NotPolicy($nonCompliantPolicy);

        self::assertFalse($policy->isCompliantIncludingPath($object, $context, $path));
    }
}
