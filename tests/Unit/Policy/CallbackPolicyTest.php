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

    public function testIsCompliantIncludingPathReturnsTrueIfCallbackReturnsTrue(): void
    {
        $object = new \stdClass();

        $path = '';

        /** @var MockObject|NormalizerContextInterface $context */
        $context = $this->getMockByCalls(NormalizerContextInterface::class, []);

        $policy = new CallbackPolicy(
            static function ($pathParameter, $objectParameter, $contextParameter) use ($path, $object, $context) {
                self::assertSame($context, $contextParameter);
                self::assertSame($object, $objectParameter);
                self::assertSame($path, $pathParameter);

                return true;
            }
        );

        self::assertTrue($policy->isCompliant($path, $object, $context));
    }

    public function testIsCompliantIncludingPathReturnsFalseIfCallbackReturnsFalse(): void
    {
        $object = new \stdClass();

        $path = '';

        /** @var MockObject|NormalizerContextInterface $context */
        $context = $this->getMockByCalls(NormalizerContextInterface::class, []);

        $policy = new CallbackPolicy(
            static function ($pathParameter, $objectParameter, $contextParameter) use ($path, $object, $context) {
                self::assertSame($context, $contextParameter);
                self::assertSame($object, $objectParameter);
                self::assertSame($path, $pathParameter);

                return false;
            }
        );

        self::assertFalse($policy->isCompliant($path, $object, $context));
    }
}
