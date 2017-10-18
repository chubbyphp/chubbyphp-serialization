<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization\Resources\Mapping;

use Chubbyphp\Serialization\SerializerRuntimeException;
use Chubbyphp\Serialization\Mapping\NormalizationFieldMappingInterface;
use Chubbyphp\Serialization\Mapping\NormalizationObjectMappingInterface;
use Chubbyphp\Tests\Serialization\Resources\Model\AbstractChildModel;

final class BaseChildModelMapping implements NormalizationObjectMappingInterface
{
    /**
     * @var ChildModelMapping
     */
    private $modelMapping;

    /**
     * @var array
     */
    private $supportedTypes;

    /**
     * @param ChildModelMapping $modelMapping
     * @param array             $supportedTypes
     */
    public function __construct(ChildModelMapping $modelMapping, array $supportedTypes)
    {
        $this->modelMapping = $modelMapping;
        $this->supportedTypes = $supportedTypes;
    }

    /**
     * @return string
     */
    public function getClass(): string
    {
        return AbstractChildModel::class;
    }

    /**
     * @param string      $path
     * @param string|null $type
     *
     * @return callable
     *
     * @throws SerializerRuntimeException
     */
    public function getNormalizationFactory(string $path, string $type = null): callable
    {
        if (null === $type) {
            throw SerializerRuntimeException::createMissingObjectType($path, $this->supportedTypes);
        }

        if ('child-model' === $type) {
            return $this->modelMapping->getNormalizationFactory($path);
        }

        throw SerializerRuntimeException::createInvalidObjectType($path, $type, $this->supportedTypes);
    }

    /**
     * @param string      $path
     * @param string|null $type
     *
     * @return NormalizationFieldMappingInterface[]
     *
     * @throws SerializerRuntimeException
     */
    public function getNormalizationFieldMappings(string $path, string $type = null): array
    {
        if (null === $type) {
            throw SerializerRuntimeException::createMissingObjectType($path, $this->supportedTypes);
        }

        if ('child-model' === $type) {
            return $this->modelMapping->getNormalizationFieldMappings($path);
        }

        throw SerializerRuntimeException::createInvalidObjectType($path, $type, $this->supportedTypes);
    }
}
