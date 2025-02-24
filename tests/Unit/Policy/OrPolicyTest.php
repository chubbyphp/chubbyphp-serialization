<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization\Unit\Policy;

use Chubbyphp\Mock\MockMethod\WithReturn;
use Chubbyphp\Mock\MockObjectBuilder;
use Chubbyphp\Serialization\Normalizer\NormalizerContextInterface;
use Chubbyphp\Serialization\Policy\OrPolicy;
use Chubbyphp\Serialization\Policy\PolicyInterface;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Chubbyphp\Serialization\Policy\OrPolicy
 *
 * @internal
 */
final class OrPolicyTest extends TestCase
{
    public function testIsCompliantIncludingPathReturnsTrueIfOnePolicyIncludingPathReturnsTrue(): void
    {
        $object = new \stdClass();

        $path = '';

        $builder = new MockObjectBuilder();

        /** @var NormalizerContextInterface $context */
        $context = $builder->create(NormalizerContextInterface::class, []);

        /** @var PolicyInterface $nonCompliantPolicy */
        $nonCompliantPolicy = $builder->create(PolicyInterface::class, [
            new WithReturn('isCompliant', [$path, $object, $context], false),
        ]);

        /** @var PolicyInterface $compliantPolicy */
        $compliantPolicy = $builder->create(PolicyInterface::class, [
            new WithReturn('isCompliant', [$path, $object, $context], true),
        ]);

        /** @var PolicyInterface $notToBeCalledPolicy */
        $notToBeCalledPolicy = $builder->create(PolicyInterface::class, []);

        $policy = new OrPolicy([$nonCompliantPolicy, $compliantPolicy, $notToBeCalledPolicy]);

        self::assertTrue($policy->isCompliant($path, $object, $context));
    }

    public function testIsCompliantIncludingReturnsFalseIfAllPoliciesReturnFalse(): void
    {
        $object = new \stdClass();

        $path = '';

        $builder = new MockObjectBuilder();

        /** @var NormalizerContextInterface $context */
        $context = $builder->create(NormalizerContextInterface::class, []);

        /** @var PolicyInterface $nonCompliantPolicy1 */
        $nonCompliantPolicy1 = $builder->create(PolicyInterface::class, [
            new WithReturn('isCompliant', [$path, $object, $context], false),
        ]);

        /** @var PolicyInterface $nonCompliantPolicy2 */
        $nonCompliantPolicy2 = $builder->create(PolicyInterface::class, [
            new WithReturn('isCompliant', [$path, $object, $context], false),
        ]);

        $policy = new OrPolicy([$nonCompliantPolicy1, $nonCompliantPolicy2]);

        self::assertFalse($policy->isCompliant($path, $object, $context));
    }
}
