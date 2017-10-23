<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization\Normalizer;

use Chubbyphp\Serialization\Normalizer\NormalizerContext;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;

/**
 * @covers \Chubbyphp\Serialization\Normalizer\NormalizerContext
 */
class NormalizerContextTest extends TestCase
{
    public function testCreate()
    {
        $context = new NormalizerContext();

        self::assertSame([], $context->getGroups());
        self::assertNull($context->getRequest());
    }

    public function testCreateWithOverridenSettings()
    {
        $request = $this->getRequest();

        $context = new NormalizerContext(['group1'], $request);

        self::assertSame(['group1'], $context->getGroups());
        self::assertSame($request, $context->getRequest());
    }

    /**
     * @return ServerRequestInterface
     */
    private function getRequest(): ServerRequestInterface
    {
        /** @var ServerRequestInterface|\PHPUnit_Framework_MockObject_MockObject $request */
        $request = $this->getMockBuilder(ServerRequestInterface::class)->getMockForAbstractClass();

        return $request;
    }
}
