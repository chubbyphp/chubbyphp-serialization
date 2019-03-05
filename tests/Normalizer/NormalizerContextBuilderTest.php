<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization\Normalizer;

use Chubbyphp\Serialization\Normalizer\NormalizerContextBuilder;
use Chubbyphp\Serialization\Normalizer\NormalizerContextInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Chubbyphp\Mock\MockByCallsTrait;

/**
 * @covers \Chubbyphp\Serialization\Normalizer\NormalizerContextBuilder
 */
class NormalizerContextBuilderTest extends TestCase
{
    use MockByCallsTrait;

    public function testCreate()
    {
        $context = NormalizerContextBuilder::create()->getContext();

        self::assertInstanceOf(NormalizerContextInterface::class, $context);

        self::assertSame([], $context->getGroups());
        self::assertNull($context->getRequest());
    }

    public function testCreateWithOverridenSettings()
    {
        /** @var ServerRequestInterface|MockObject $request */
        $request = $this->getMockByCalls(ServerRequestInterface::class);

        $context = NormalizerContextBuilder::create()
            ->setGroups(['group1'])
            ->setRequest($request)
            ->getContext();

        self::assertInstanceOf(NormalizerContextInterface::class, $context);

        self::assertSame(['group1'], $context->getGroups());
        self::assertSame($request, $context->getRequest());
    }

    public function testCreateSetNullRequest()
    {
        $context = NormalizerContextBuilder::create()
            ->setRequest()
            ->getContext();

        self::assertInstanceOf(NormalizerContextInterface::class, $context);

        self::assertNull($context->getRequest());
    }
}
