<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Mapping;

use Chubbyphp\Serialization\Serializer\Link\LinkSerializerInterface;

interface LinkMappingInterface
{
    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return LinkSerializerInterface
     */
    public function getLinkSerializer(): LinkSerializerInterface;
}
