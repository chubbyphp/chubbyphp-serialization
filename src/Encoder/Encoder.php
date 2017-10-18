<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Encoder;

use Chubbyphp\Serialization\SerializerLogicException;

final class Encoder implements EncoderInterface
{
    /**
     * @var TypeEncoderInterface[]
     */
    private $encoderTypes;

    /**
     * @param TypeEncoderInterface[] $encoderTypes
     */
    public function __construct(array $encoderTypes)
    {
        $this->encoderTypes = [];
        foreach ($encoderTypes as $encoderType) {
            $this->addTypeEncoder($encoderType);
        }
    }

    /**
     * @param TypeEncoderInterface $encoderType
     */
    private function addTypeEncoder(TypeEncoderInterface $encoderType)
    {
        $this->encoderTypes[$encoderType->getContentType()] = $encoderType;
    }

    /**
     * @return array
     */
    public function getContentTypes(): array
    {
        return array_keys($this->encoderTypes);
    }

    /**
     * @param array  $data
     * @param string $contentType
     *
     * @return string
     *
     * @throws SerializerLogicException
     */
    public function encode(array $data, string $contentType): string
    {
        if (isset($this->encoderTypes[$contentType])) {
            return $this->encoderTypes[$contentType]->encode($data);
        }

        throw SerializerLogicException::createMissingContentType($contentType);
    }
}
