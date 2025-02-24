<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization\Unit\Normalizer;

use Chubbyphp\Mock\MockObjectBuilder;
use Chubbyphp\Serialization\Normalizer\NormalizerContext;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;

/**
 * @covers \Chubbyphp\Serialization\Normalizer\NormalizerContext
 *
 * @internal
 */
final class NormalizerContextTest extends TestCase
{
    public function testCreate(): void
    {
        $context = new NormalizerContext();

        self::assertNull($context->getRequest());
        self::assertSame([], $context->getAttributes());
        self::assertNull($context->getAttribute('nonExistingAttribute'));
        self::assertSame('default', $context->getAttribute('nonExistingAttribute', 'default'));
    }

    public function testCreateWithOverriddenSettings(): void
    {
        $builder = new MockObjectBuilder();

        /** @var MockObject|ServerRequestInterface $request */
        $request = $builder->create(ServerRequestInterface::class, []);

        $context = new NormalizerContext(
            $request,
            ['attribute' => 'value']
        );

        self::assertSame($request, $context->getRequest());
        self::assertSame(['attribute' => 'value'], $context->getAttributes());
        self::assertSame('value', $context->getAttribute('attribute'));
    }

    public function testWithAttributes(): void
    {
        $builder = new MockObjectBuilder();

        /** @var MockObject|ServerRequestInterface $request */
        $request = $builder->create(ServerRequestInterface::class, []);

        $context = new NormalizerContext($request, ['attribute' => 'value']);

        $newContext = $context->withAttributes(['otherAttribute' => 'value2']);

        self::assertNotSame($context, $newContext);

        self::assertSame(['otherAttribute' => 'value2'], $newContext->getAttributes());
        self::assertSame(['attribute' => 'value'], $context->getAttributes());
    }

    public function testWithAttribute(): void
    {
        $builder = new MockObjectBuilder();

        /** @var MockObject|ServerRequestInterface $request */
        $request = $builder->create(ServerRequestInterface::class, []);

        $context = new NormalizerContext($request, ['attribute' => 'value'], ['allowed_field']);

        $newContext = $context->withAttribute('otherAttribute', 'value2');

        self::assertNotSame($context, $newContext);

        self::assertSame(['attribute' => 'value', 'otherAttribute' => 'value2'], $newContext->getAttributes());
        self::assertSame(['attribute' => 'value'], $context->getAttributes());
    }
}
