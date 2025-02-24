<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization\Unit\Policy;

use Chubbyphp\Mock\MockMethod\WithReturn;
use Chubbyphp\Mock\MockObjectBuilder;
use Chubbyphp\Serialization\Normalizer\NormalizerContextInterface;
use Chubbyphp\Serialization\Policy\AndPolicy;
use Chubbyphp\Serialization\Policy\PolicyInterface;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Chubbyphp\Serialization\Policy\AndPolicy
 *
 * @internal
 */
final class AndPolicyTest extends TestCase
{
    public function testIsCompliantIncludingPathReturnsTrueWithMultipleCompliantPolicies(): void
    {
        $object = new \stdClass();

        $path = '';

        $builder = new MockObjectBuilder();

        /** @var NormalizerContextInterface $context */
        $context = $builder->create(NormalizerContextInterface::class, []);

        /** @var PolicyInterface $compliantPolicy1 */
        $compliantPolicy1 = $builder->create(PolicyInterface::class, [
            new WithReturn('isCompliant', [$path, $object, $context], true),
        ]);

        /** @var PolicyInterface $compliantPolicy2 */
        $compliantPolicy2 = $builder->create(PolicyInterface::class, [
            new WithReturn('isCompliant', [$path, $object, $context], true),
        ]);

        $policy = new AndPolicy([$compliantPolicy1, $compliantPolicy2]);

        self::assertTrue($policy->isCompliant($path, $object, $context));
    }

    public function testIsCompliantIncludingPathReturnsFalseWithNonCompliantIncludingPathPolicy(): void
    {
        $object = new \stdClass();

        $path = '';

        $builder = new MockObjectBuilder();

        /** @var NormalizerContextInterface $context */
        $context = $builder->create(NormalizerContextInterface::class, []);

        /** @var PolicyInterface $compliantPolicy */
        $compliantPolicy = $builder->create(PolicyInterface::class, [
            new WithReturn('isCompliant', [$path, $object, $context], true),
        ]);

        /** @var PolicyInterface $nonCompliantPolicy */
        $nonCompliantPolicy = $builder->create(PolicyInterface::class, [
            new WithReturn('isCompliant', [$path, $object, $context], false),
        ]);

        /** @var PolicyInterface $notExpectedToBeCalledPolicy */
        $notExpectedToBeCalledPolicy = $builder->create(PolicyInterface::class, []);

        $policy = new AndPolicy([$compliantPolicy, $nonCompliantPolicy, $notExpectedToBeCalledPolicy]);

        self::assertFalse($policy->isCompliant($path, $object, $context));
    }
}
