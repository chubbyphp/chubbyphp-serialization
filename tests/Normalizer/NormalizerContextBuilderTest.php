<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization\Normalizer;

use Chubbyphp\Serialization\Normalizer\NormalizerContextBuilder;
use Chubbyphp\Serialization\Normalizer\NormalizerContextInterface;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;

/**
 * @covers \Chubbyphp\Serialization\Normalizer\NormalizerContextBuilder
 */
class NormalizerContextBuilderTest extends TestCase
{
    public function testCreate()
    {
        $context = NormalizerContextBuilder::create()->getContext();

        self::assertInstanceOf(NormalizerContextInterface::class, $context);

        self::assertSame([], $context->getGroups());
        self::assertNull($context->getRequest());
    }

    public function testCreateWithOverridenSettings()
    {
        $request = $this->getRequest();

        $context = NormalizerContextBuilder::create()
            ->setGroups(['group1'])
            ->setRequest($request)
            ->getContext();

        self::assertInstanceOf(NormalizerContextInterface::class, $context);

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
