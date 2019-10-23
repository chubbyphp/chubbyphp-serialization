<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization\Unit\Policy;

use Chubbyphp\Mock\MockByCallsTrait;
use Chubbyphp\Serialization\Normalizer\NormalizerContextInterface;
use Chubbyphp\Serialization\Policy\CallbackPolicy;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Chubbyphp\Serialization\Policy\CallbackPolicy
 *
 * @internal
 */
final class CallbackPolicyTest extends TestCase
{
    use MockByCallsTrait;

    public function testIsCompliantReturnsTrueIfCallbackReturnsTrue(): void
    {
        $object = new \stdClass();

        /** @var NormalizerContextInterface|MockObject $context */
        $context = $this->getMockByCalls(NormalizerContextInterface::class, []);

        $policy = new CallbackPolicy(function ($contextParameter, $objectParameter) use ($context, $object) {
            self::assertSame($context, $contextParameter);
            self::assertSame($object, $objectParameter);

            return true;
        });

        self::assertTrue($policy->isCompliant($context, $object));
    }

    public function testIsCompliantReturnsFalseIfCallbackReturnsFalse(): void
    {
        $object = new \stdClass();

        /** @var NormalizerContextInterface|MockObject $context */
        $context = $this->getMockByCalls(NormalizerContextInterface::class, []);

        $policy = new CallbackPolicy(function ($contextParameter, $objectParameter) use ($context, $object) {
            self::assertSame($context, $contextParameter);
            self::assertSame($object, $objectParameter);

            return false;
        });

        self::assertFalse($policy->isCompliant($context, $object));
    }
}
