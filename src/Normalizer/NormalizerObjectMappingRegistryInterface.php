<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Normalizer;

use Chubbyphp\Serialization\SerializerLogicException;
use Chubbyphp\Serialization\Mapping\NormalizationObjectMappingInterface;

interface NormalizerObjectMappingRegistryInterface
{
    /**
     * @param string $class
     *
     * @return NormalizationObjectMappingInterface
     *
     * @throws SerializerLogicException
     */
    public function getObjectMapping(string $class): NormalizationObjectMappingInterface;
}
