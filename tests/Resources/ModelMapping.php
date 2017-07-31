<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization\Resources;

use Chubbyphp\Serialization\Link\Link;
use Chubbyphp\Serialization\Mapping\FieldMapping;
use Chubbyphp\Serialization\Mapping\FieldMappingInterface;
use Chubbyphp\Serialization\Mapping\LinkMapping;
use Chubbyphp\Serialization\Mapping\LinkMappingInterface;
use Chubbyphp\Serialization\Mapping\ObjectMappingInterface;
use Chubbyphp\Serialization\Serializer\Link\CallbackLinkSerializer;
use Psr\Http\Message\ServerRequestInterface as Request;

final class ModelMapping implements ObjectMappingInterface
{
    /**
     * @return string
     */
    public function getClass(): string
    {
        return Model::class;
    }

    /**
     * @return FieldMappingInterface[]
     */
    public function getFieldMappings(): array
    {
        return [
            new FieldMapping('name'),
        ];
    }

    /**
     * @return FieldMappingInterface[]
     */
    public function getEmbeddedFieldMappings(): array
    {
        return [
            new FieldMapping('name'),
        ];
    }

    /**
     * @return LinkMappingInterface[]
     */
    public function getLinkMappings(): array
    {
        return [
            new LinkMapping('name', new CallbackLinkSerializer(function (Request $request, $object) {
                return new Link('http://test.com/models', Link::METHOD_GET);
            })),
        ];
    }
}
