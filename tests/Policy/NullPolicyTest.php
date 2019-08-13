<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization\Policy;

use Chubbyphp\Mock\MockByCallsTrait;
use Chubbyphp\Serialization\Normalizer\NormalizerContextInterface;
use Chubbyphp\Serialization\Policy\NullPolicy;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Chubbyphp\Serialization\Policy\NullPolicy
 */
class NullPolicyTest extends TestCase
{
    use MockByCallsTrait;

    public function testIsCompliantReturnsTrue()
    {
        $object = new \stdClass();

        /** @var NormalizerContextInterface|MockObject $context */
        $context = $this->getMockByCalls(NormalizerContextInterface::class);

        $policy = new NullPolicy();

        self::assertTrue($policy->isCompliant($context, $object));
    }
}
