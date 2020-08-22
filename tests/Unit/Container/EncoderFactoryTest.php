<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization\Unit\Container;

use Chubbyphp\Mock\Call;
use Chubbyphp\Mock\MockByCallsTrait;
use Chubbyphp\Serialization\Container\EncoderFactory;
use Chubbyphp\Serialization\Encoder\EncoderInterface;
use Chubbyphp\Serialization\Encoder\TypeEncoderInterface;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

/**
 * @covers \Chubbyphp\Serialization\Container\EncoderFactory
 *
 * @internal
 */
final class EncoderFactoryTest extends TestCase
{
    use MockByCallsTrait;

    public function testInvoke(): void
    {
        /** @var TypeEncoderInterface $typeEncoder */
        $typeEncoder = $this->getMockByCalls(TypeEncoderInterface::class, [
            Call::create('getContentType')->with()->willReturn('application/json'),
        ]);

        /** @var ContainerInterface $container */
        $container = $this->getMockByCalls(ContainerInterface::class, [
            Call::create('get')
                ->with(TypeEncoderInterface::class.'[]')
                ->willReturn([$typeEncoder]),
        ]);

        $factory = new EncoderFactory();

        $negotiator = $factory($container);

        self::assertInstanceOf(EncoderInterface::class, $negotiator);
    }
}
