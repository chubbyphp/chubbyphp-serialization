<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization\Unit\Policy;

use Chubbyphp\Mock\MockMethod\WithReturn;
use Chubbyphp\Mock\MockObjectBuilder;
use Chubbyphp\Serialization\Normalizer\NormalizerContextInterface;
use Chubbyphp\Serialization\Policy\GroupPolicy;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Chubbyphp\Serialization\Policy\GroupPolicy
 *
 * @internal
 */
final class GroupPolicyTest extends TestCase
{
    public function testIsCompliantIncludingPathReturnsTrueIfNoGroupsAreSet(): void
    {
        $object = new \stdClass();

        $path = '';

        $builder = new MockObjectBuilder();

        /** @var NormalizerContextInterface $context */
        $context = $builder->create(NormalizerContextInterface::class, []);

        $policy = new GroupPolicy([]);

        self::assertTrue($policy->isCompliant($path, $object, $context));
    }

    public function testIsCompliantIncludingPathReturnsTrueWithDefaultValues(): void
    {
        $object = new \stdClass();

        $path = '';

        $builder = new MockObjectBuilder();

        /** @var NormalizerContextInterface $context */
        $context = $builder->create(NormalizerContextInterface::class, [
            new WithReturn('getAttribute', [GroupPolicy::ATTRIBUTE_GROUPS, [GroupPolicy::GROUP_DEFAULT]], [GroupPolicy::GROUP_DEFAULT]),
        ]);

        $policy = new GroupPolicy();

        self::assertTrue($policy->isCompliant($path, $object, $context));
    }

    public function testIsCompliantIncludingPathReturnsTrueIfOneGroupMatches(): void
    {
        $object = new \stdClass();

        $path = '';

        $builder = new MockObjectBuilder();

        /** @var NormalizerContextInterface $context */
        $context = $builder->create(NormalizerContextInterface::class, [
            new WithReturn('getAttribute', [GroupPolicy::ATTRIBUTE_GROUPS, [GroupPolicy::GROUP_DEFAULT]], ['group2']),
        ]);

        $policy = new GroupPolicy(['group1', 'group2']);

        self::assertTrue($policy->isCompliant($path, $object, $context));
    }

    public function testIsCompliantIncludingPathReturnsFalseIfNoGroupsAreSetInContext(): void
    {
        $object = new \stdClass();

        $path = '';

        $builder = new MockObjectBuilder();

        /** @var NormalizerContextInterface $context */
        $context = $builder->create(NormalizerContextInterface::class, [
            new WithReturn('getAttribute', [GroupPolicy::ATTRIBUTE_GROUPS, [GroupPolicy::GROUP_DEFAULT]], []),
        ]);

        $policy = new GroupPolicy(['group1', 'group2']);

        self::assertFalse($policy->isCompliant($path, $object, $context));
    }

    public function testIsCompliantIncludingPathReturnsFalseIfNoGroupsMatch(): void
    {
        $object = new \stdClass();

        $path = '';

        $builder = new MockObjectBuilder();

        /** @var NormalizerContextInterface $context */
        $context = $builder->create(NormalizerContextInterface::class, [
            new WithReturn('getAttribute', [GroupPolicy::ATTRIBUTE_GROUPS, [GroupPolicy::GROUP_DEFAULT]], ['unknownGroup']),
        ]);

        $policy = new GroupPolicy(['group1', 'group2']);

        self::assertFalse($policy->isCompliant($path, $object, $context));
    }
}
