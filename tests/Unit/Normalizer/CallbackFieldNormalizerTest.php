<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization\Unit\Normalizer;

use Chubbyphp\Mock\MockObjectBuilder;
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
    public function testNormalizeField(): void
    {
        $builder = new MockObjectBuilder();

        /** @var MockObject|NormalizerContextInterface $normalizerContext */
        $normalizerContext = $builder->create(NormalizerContextInterface::class, []);

        $object = new \stdClass();

        $fieldNormalizer = new CallbackFieldNormalizer(
            static fn (string $path, $object, NormalizerContextInterface $context, ?NormalizerInterface $normalizer = null) => 'name'
        );

        self::assertSame('name', $fieldNormalizer->normalizeField('name', $object, $normalizerContext));
    }
}
