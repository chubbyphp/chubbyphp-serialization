<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization;

use Chubbyphp\Serialization\Transformer\TransformerInterface as ContenTypeTransformerInterface;
use Chubbyphp\Serialization\Transformer;

/**
 * @covers \Chubbyphp\Serialization\Transformer
 */
final class TransformerTest extends \PHPUnit_Framework_TestCase
{
    public function testWithContentTypes()
    {
        $transformers = [
            $this->getJsonTransformer(),
            $this->getUrlEncodedTransformer(),
        ];

        $transformer = new Transformer($transformers);

        self::assertEquals(['application/json', 'application/x-www-form-urlencoded'], $transformer->getContentTypes());
    }

    public function testWithKnownTransformers()
    {
        $transformers = [
            $this->getJsonTransformer(),
            $this->getUrlEncodedTransformer(),
        ];

        $transformer = new Transformer($transformers);

        self::assertEquals('{"key":"value"}', $transformer->transform(['key' => 'value'], 'application/json'));
        self::assertEquals('key=value', $transformer->transform(['key' => 'value'], 'application/x-www-form-urlencoded'));
    }

    public function testWithUnknownTransformers()
    {
        self::expectException(\InvalidArgumentException::class);
        self::expectExceptionMessage('There is no transformer for content-type: application/x-yaml');

        $transformer = new Transformer([]);

        $transformer->transform(['key' => 'value'], 'application/x-yaml');
    }

    /**
     * @return ContenTypeTransformerInterface
     */
    private function getJsonTransformer(): ContenTypeTransformerInterface
    {
        /** @var ContenTypeTransformerInterface|\PHPUnit_Framework_MockObject_MockObject $mapping */
        $mapping = $this->getMockBuilder(ContenTypeTransformerInterface::class)->getMockForAbstractClass();

        $mapping->expects(self::any())->method('getContentType')->willReturn('application/json');
        $mapping->expects(self::any())->method('transform')->willReturnCallback(
            function (array $data) {
                return json_encode($data);
            }
        );

        return $mapping;
    }

    /**
     * @return ContenTypeTransformerInterface
     */
    private function getUrlEncodedTransformer(): ContenTypeTransformerInterface
    {
        /** @var ContenTypeTransformerInterface|\PHPUnit_Framework_MockObject_MockObject $mapping */
        $mapping = $this->getMockBuilder(ContenTypeTransformerInterface::class)->getMockForAbstractClass();

        $mapping->expects(self::any())->method('getContentType')->willReturn('application/x-www-form-urlencoded');
        $mapping->expects(self::any())->method('transform')->willReturnCallback(
            function (array $data) {
                return http_build_query($data);
            }
        );

        return $mapping;
    }
}
