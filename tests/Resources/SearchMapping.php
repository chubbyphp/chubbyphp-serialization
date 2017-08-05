<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization\Resources;

use Chubbyphp\Serialization\Accessor\PropertyAccessor;
use Chubbyphp\Serialization\Link\Link;
use Chubbyphp\Serialization\Mapping\FieldMapping;
use Chubbyphp\Serialization\Mapping\FieldMappingInterface;
use Chubbyphp\Serialization\Mapping\LinkMapping;
use Chubbyphp\Serialization\Mapping\LinkMappingInterface;
use Chubbyphp\Serialization\Mapping\ObjectMappingInterface;
use Chubbyphp\Serialization\Serializer\Field\CollectionSerializer;
use Chubbyphp\Serialization\Serializer\Field\ValueSerializer;
use Chubbyphp\Serialization\Serializer\Link\CallbackLinkSerializer;
use Psr\Http\Message\ServerRequestInterface as Request;

final class SearchMapping implements ObjectMappingInterface
{
    /**
     * @return string
     */
    public function getClass(): string
    {
        return Search::class;
    }

    /**
     * @return FieldMappingInterface[]
     */
    public function getFieldMappings(): array
    {
        return [
            new FieldMapping('page'),
            new FieldMapping('per_page', new ValueSerializer(new PropertyAccessor('perPage'))),
            new FieldMapping('search'),
            new FieldMapping('sort'),
            new FieldMapping('order'),
        ];
    }

    /**
     * @return FieldMappingInterface[]
     */
    public function getEmbeddedFieldMappings(): array
    {
        return [
            new FieldMapping('items', new CollectionSerializer(new PropertyAccessor('items'))),
        ];
    }

    /**
     * @return LinkMappingInterface[]
     */
    public function getLinkMappings(): array
    {
        return [
            new LinkMapping('self', new CallbackLinkSerializer(
                function (Request $request, Search $search, array $fields) {
                    return new Link('http://test.com/items/?'.http_build_query($fields), Link::METHOD_GET);
                }
            )),
            new LinkMapping('create', new CallbackLinkSerializer(function () {
                return new Link('http://test.com/items/', Link::METHOD_POST);
            })),
        ];
    }
}
