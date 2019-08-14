<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Normalizer;

use Chubbyphp\Serialization\Mapping\NormalizationObjectMappingInterface;
use Chubbyphp\Serialization\SerializerLogicException;

interface NormalizerObjectMappingRegistryInterface
{
    /**
     * @param string $class
     *
     * @throws SerializerLogicException
     *
     * @return NormalizationObjectMappingInterface
     */
    public function getObjectMapping(string $class): NormalizationObjectMappingInterface;
}
