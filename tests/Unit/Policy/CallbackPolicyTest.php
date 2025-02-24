<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization\Unit\Policy;

use Chubbyphp\Mock\MockObjectBuilder;
use Chubbyphp\Serialization\Normalizer\NormalizerContextInterface;
use Chubbyphp\Serialization\Policy\CallbackPolicy;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Chubbyphp\Serialization\Policy\CallbackPolicy
 *
 * @internal
 */
final class CallbackPolicyTest extends TestCase
{
    public function testIsCompliantIncludingPathReturnsTrueIfCallbackReturnsTrue(): void
    {
        $object = new \stdClass();

        $path = '';

        $builder = new MockObjectBuilder();

        /** @var NormalizerContextInterface $context */
        $context = $builder->create(NormalizerContextInterface::class, []);

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

        $builder = new MockObjectBuilder();

        /** @var NormalizerContextInterface $context */
        $context = $builder->create(NormalizerContextInterface::class, []);

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
