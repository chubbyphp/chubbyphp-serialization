<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Serializer\Field;

use Chubbyphp\Serialization\Accessor\AccessorInterface;
use Chubbyphp\Serialization\SerializerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;

final class ValueSerializer implements FieldSerializerInterface
{
    /**
     * @var AccessorInterface
     */
    private $accessor;

    /**
     * @var string|null
     */
    private $cast;

    const CAST_BOOL = 'bool';
    const CAST_FLOAT = 'float';
    const CAST_INT = 'int';

    /**
     * @param AccessorInterface $accessor
     * @param string            $cast
     */
    public function __construct(AccessorInterface $accessor, string $cast = null)
    {
        $this->accessor = $accessor;

        if (null !== $cast) {
            $supportedCasts = [self::CAST_BOOL, self::CAST_FLOAT, self::CAST_INT];
            if (!in_array($cast, $supportedCasts, true)) {
                throw new \InvalidArgumentException(
                    sprintf('Cast %s is not support, supported casts: %s', $cast, implode(', ', $supportedCasts))
                );
            }
            $this->cast = $cast;
        }
    }

    /**
     * @param string                   $path
     * @param Request                  $request
     * @param object                   $object
     * @param SerializerInterface|null $serializer
     *
     * @return mixed
     */
    public function serializeField(string $path, Request $request, $object, SerializerInterface $serializer = null)
    {
        return $this->castValue($this->accessor->getValue($object));
    }

    /**
     * @param mixed $value
     *
     * @return mixed
     */
    private function castValue($value)
    {
        if (is_array($value)) {
            $castedValue = [];
            foreach ($value as $key => $subValue) {
                $castedValue[$key] = $this->castValue($subValue);
            }

            return $castedValue;
        }

        if (null !== $value && null !== $this->cast) {
            switch ($this->cast) {
                case self::CAST_BOOL:
                    return (bool) $value;
                case self::CAST_FLOAT:
                    return (float) $value;
                    break;
                case self::CAST_INT:
                    return (int) $value;
                    break;
            }
        }

        return $value;
    }
}
