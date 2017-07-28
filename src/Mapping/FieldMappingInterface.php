<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Mapping;

use Chubbyphp\Serialization\Serializer\Field\FieldSerializerInterface;

interface FieldMappingInterface
{
    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return FieldSerializerInterface
     */
    public function getFieldSerializer(): FieldSerializerInterface;
}
