<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization\Unit\Policy;

use Chubbyphp\Mock\Call;
use Chubbyphp\Mock\MockByCallsTrait;
use Chubbyphp\Serialization\Normalizer\NormalizerContextInterface;
use Chubbyphp\Serialization\Policy\GroupPolicy;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Chubbyphp\Serialization\Policy\GroupPolicy
 *
 * @internal
 */
final class GroupPolicyTest extends TestCase
{
    use MockByCallsTrait;

    public function testIsCompliantIncludingPathReturnsTrueIfNoGroupsAreSet(): void
    {
        $object = new \stdClass();

        $path = '';

        /** @var NormalizerContextInterface|MockObject $context */
        $context = $this->getMockByCalls(NormalizerContextInterface::class);

        $policy = new GroupPolicy([]);

        self::assertTrue($policy->isCompliantIncludingPath($path, $object, $context));
    }

    public function testIsCompliantIncludingPathReturnsTrueWithDefaultValues(): void
    {
        $object = new \stdClass();

        $path = '';

        /** @var NormalizerContextInterface|MockObject $context */
        $context = $this->getMockByCalls(NormalizerContextInterface::class, [
            Call::create('getAttribute')
                ->with(GroupPolicy::ATTRIBUTE_GROUPS, [GroupPolicy::GROUP_DEFAULT])
                ->willReturn([GroupPolicy::GROUP_DEFAULT]),
        ]);

        $policy = new GroupPolicy();

        self::assertTrue($policy->isCompliantIncludingPath($path, $object, $context));
    }

    public function testIsCompliantIncludingPathReturnsTrueIfOneGroupMatches(): void
    {
        $object = new \stdClass();

        $path = '';

        /** @var NormalizerContextInterface|MockObject $context */
        $context = $this->getMockByCalls(NormalizerContextInterface::class, [
            Call::create('getAttribute')
                ->with(GroupPolicy::ATTRIBUTE_GROUPS, [GroupPolicy::GROUP_DEFAULT])
                ->willReturn(['group2']),
        ]);

        $policy = new GroupPolicy(['group1', 'group2']);

        self::assertTrue($policy->isCompliantIncludingPath($path, $object, $context));
    }

    public function testIsCompliantIncludingPathReturnsFalseIfNoGroupsAreSetInContext(): void
    {
        $object = new \stdClass();

        $path = '';

        /** @var NormalizerContextInterface|MockObject $context */
        $context = $this->getMockByCalls(NormalizerContextInterface::class, [
            Call::create('getAttribute')
                ->with(GroupPolicy::ATTRIBUTE_GROUPS, [GroupPolicy::GROUP_DEFAULT])
                ->willReturn([]),
        ]);

        $policy = new GroupPolicy(['group1', 'group2']);

        self::assertFalse($policy->isCompliantIncludingPath($path, $object, $context));
    }

    public function testIsCompliantIncludingPathReturnsFalseIfNoGroupsMatch(): void
    {
        $object = new \stdClass();

        $path = '';

        /** @var NormalizerContextInterface|MockObject $context */
        $context = $this->getMockByCalls(NormalizerContextInterface::class, [
            Call::create('getAttribute')
                ->with(GroupPolicy::ATTRIBUTE_GROUPS, [GroupPolicy::GROUP_DEFAULT])
                ->willReturn(['unknownGroup']),
        ]);

        $policy = new GroupPolicy(['group1', 'group2']);

        self::assertFalse($policy->isCompliantIncludingPath($path, $object, $context));
    }
}
