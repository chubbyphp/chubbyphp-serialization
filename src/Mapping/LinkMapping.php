<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Mapping;

use Chubbyphp\Serialization\Serializer\Link\LinkSerializerInterface;

final class LinkMapping implements LinkMappingInterface
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var LinkSerializerInterface
     */
    private $linkSerializer;

    /**
     * @param string                  $name
     * @param LinkSerializerInterface $linkSerializer
     */
    public function __construct(string $name, LinkSerializerInterface $linkSerializer)
    {
        $this->name = $name;
        $this->linkSerializer = $linkSerializer;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return LinkSerializerInterface
     */
    public function getLinkSerializer(): LinkSerializerInterface
    {
        return $this->linkSerializer;
    }
}
