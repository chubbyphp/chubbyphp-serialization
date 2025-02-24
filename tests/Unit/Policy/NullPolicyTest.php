<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization\Unit\Policy;

use Chubbyphp\Mock\MockObjectBuilder;
use Chubbyphp\Serialization\Normalizer\NormalizerContextInterface;
use Chubbyphp\Serialization\Policy\NullPolicy;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Chubbyphp\Serialization\Policy\NullPolicy
 *
 * @internal
 */
final class NullPolicyTest extends TestCase
{
    public function testIsCompliantIncludingReturnsTrue(): void
    {
        $object = new \stdClass();

        $path = '';

        $builder = new MockObjectBuilder();

        /** @var NormalizerContextInterface $context */
        $context = $builder->create(NormalizerContextInterface::class, []);

        $policy = new NullPolicy();

        self::assertTrue($policy->isCompliant($path, $object, $context));
    }
}
