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

final class ItemMapping implements ObjectMappingInterface
{
    /**
     * @return string
     */
    public function getClass(): string
    {
        return Item::class;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'item';
    }

    /**
     * @return FieldMappingInterface[]
     */
    public function getFieldMappings(): array
    {
        return [
            new FieldMapping('id'),
            new FieldMapping('name'),
            new FieldMapping('progress'),
            new FieldMapping('active'),
        ];
    }

    /**
     * @return FieldMappingInterface[]
     */
    public function getEmbeddedFieldMappings(): array
    {
        return [];
    }

    /**
     * @return LinkMappingInterface[]
     */
    public function getLinkMappings(): array
    {
        return [
            new LinkMapping('read', new CallbackLinkSerializer(function (Request $request, Item $item) {
                return new Link('http://test.com/items/'.$item->getId(), Link::METHOD_GET);
            })),
            new LinkMapping('update', new CallbackLinkSerializer(function (Request $request, Item $item) {
                return new Link('http://test.com/items/'.$item->getId(), Link::METHOD_PUT);
            })),
            new LinkMapping('delete', new CallbackLinkSerializer(function (Request $request, Item $item) {
                return new Link('http://test.com/items/'.$item->getId(), Link::METHOD_DELETE);
            })),
        ];
    }
}
