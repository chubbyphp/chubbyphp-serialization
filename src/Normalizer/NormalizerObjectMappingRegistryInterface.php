<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Normalizer;

use Chubbyphp\Serialization\Mapping\NormalizationObjectMappingInterface;
use Chubbyphp\Serialization\SerializerLogicException;

interface NormalizerObjectMappingRegistryInterface
{
    /**
     * @throws SerializerLogicException
     */
    public function getObjectMapping(string $class): NormalizationObjectMappingInterface;
}
