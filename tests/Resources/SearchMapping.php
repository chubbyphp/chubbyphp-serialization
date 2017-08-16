<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization\Resources;

use Chubbyphp\Serialization\Accessor\MethodAccessor;
use Chubbyphp\Serialization\Accessor\PropertyAccessor;
use Chubbyphp\Serialization\Link\Link;
use Chubbyphp\Serialization\Link\NullLink;
use Chubbyphp\Serialization\Mapping\FieldMapping;
use Chubbyphp\Serialization\Mapping\FieldMappingInterface;
use Chubbyphp\Serialization\Mapping\LinkMapping;
use Chubbyphp\Serialization\Mapping\LinkMappingInterface;
use Chubbyphp\Serialization\Mapping\ObjectMappingInterface;
use Chubbyphp\Serialization\Serializer\Field\CollectionFieldSerializer;
use Chubbyphp\Serialization\Serializer\Field\ObjectFieldSerializer;
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
     * @return string
     */
    public function getType(): string
    {
        return 'search';
    }

    /**
     * @return FieldMappingInterface[]
     */
    public function getFieldMappings(): array
    {
        return [
            new FieldMapping('page'),
            new FieldMapping('perPage'),
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
            new FieldMapping('mainItem', new ObjectFieldSerializer(new MethodAccessor('getMainItem'))),
            new FieldMapping('items', new CollectionFieldSerializer(new PropertyAccessor('items'))),
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
            new LinkMapping('prev', new CallbackLinkSerializer(
                function (Request $request, Search $search, array $fields) {
                    if ($fields['page'] > 1) {
                        $fields['page'] -= 1;

                        return new Link('http://test.com/items/?'.http_build_query($fields), Link::METHOD_GET);
                    }

                    return new NullLink();
                }
            )),
            new LinkMapping('create', new CallbackLinkSerializer(function () {
                return new Link('http://test.com/items/', Link::METHOD_POST);
            })),
        ];
    }
}
