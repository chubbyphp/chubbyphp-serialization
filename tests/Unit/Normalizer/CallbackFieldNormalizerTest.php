<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization\Unit\Normalizer;

use Chubbyphp\Mock\MockByCallsTrait;
use Chubbyphp\Serialization\Normalizer\CallbackFieldNormalizer;
use Chubbyphp\Serialization\Normalizer\NormalizerContextInterface;
use Chubbyphp\Serialization\Normalizer\NormalizerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Chubbyphp\Serialization\Normalizer\CallbackFieldNormalizer
 *
 * @internal
 */
final class CallbackFieldNormalizerTest extends TestCase
{
    use MockByCallsTrait;

    public function testNormalizeField(): void
    {
        /** @var MockObject|NormalizerContextInterface $normalizerContext */
        $normalizerContext = $this->getMockByCalls(NormalizerContextInterface::class);

        $object = new \stdClass();

        $fieldNormalizer = new CallbackFieldNormalizer(
            static fn (string $path, $object, NormalizerContextInterface $context, ?NormalizerInterface $normalizer = null) => 'name'
        );

        self::assertSame('name', $fieldNormalizer->normalizeField('name', $object, $normalizerContext));
    }
}
