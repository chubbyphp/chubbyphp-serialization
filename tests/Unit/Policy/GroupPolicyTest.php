<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization\Unit\Policy;

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

    public function testIsCompliantReturnsTrueIfNoGroupsAreSet(): void
    {
        $object = new \stdClass();

        /** @var NormalizerContextInterface|MockObject $context */
        $context = $this->getMockByCalls(NormalizerContextInterface::class);

        $policy = new GroupPolicy([]);

        self::assertTrue($policy->isCompliant($context, $object));
    }

    public function testIsCompliantReturnsTrueWithDefaultValues(): void
    {
        $object = new \stdClass();

        /** @var NormalizerContextInterface|MockObject $context */
        $context = $this->getNormalizerContextWithGroupAttribute(null);

        $policy = new GroupPolicy();

        self::assertTrue($policy->isCompliant($context, $object));
    }

    public function testIsCompliantReturnsTrueIfOneGroupMatches(): void
    {
        $object = new \stdClass();

        /** @var NormalizerContextInterface|MockObject $context */
        $context = $this->getNormalizerContextWithGroupAttribute(['group2']);

        $policy = new GroupPolicy(['group1', 'group2']);

        self::assertTrue($policy->isCompliant($context, $object));
    }

    public function testIsCompliantReturnsFalseIfNoGroupsAreSetInContext(): void
    {
        $object = new \stdClass();

        /** @var NormalizerContextInterface|MockObject $context */
        $context = $this->getNormalizerContextWithGroupAttribute([]);

        $policy = new GroupPolicy(['group1', 'group2']);

        self::assertFalse($policy->isCompliant($context, $object));
    }

    public function testIsCompliantReturnsFalseIfNoGroupsMatch(): void
    {
        $object = new \stdClass();

        /** @var NormalizerContextInterface|MockObject $context */
        $context = $this->getNormalizerContextWithGroupAttribute(['unknownGroup']);

        $policy = new GroupPolicy(['group1', 'group2']);

        self::assertFalse($policy->isCompliant($context, $object));
    }

    /**
     * @param array|null $groups
     *
     * @return NormalizerContextInterface
     */
    private function getNormalizerContextWithGroupAttribute(array $groups = null): NormalizerContextInterface
    {
        return new class($groups) implements NormalizerContextInterface {
            private $groups;

            public function __construct($groups)
            {
                $this->groups = $groups;
            }

            public function getGroups(): array
            {
                return [];
            }

            public function getRequest()
            {
                return null;
            }

            public function getAttributes(): array
            {
                return [];
            }

            public function getAttribute(string $name, $default = null)
            {
                return $this->groups ?? $default;
            }

            public function withAttribute(string $name, $value): NormalizerContextInterface
            {
                return $this;
            }
        };
    }
}
