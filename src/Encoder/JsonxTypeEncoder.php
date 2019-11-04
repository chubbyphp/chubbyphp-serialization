<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Encoder;

/**
 * @see https://www.ibm.com/support/knowledgecenter/SS9H2Y_7.6.0/com.ibm.dp.doc/json_jsonx.html
 */
final class JsonxTypeEncoder implements TypeEncoderInterface
{
    const DATATYPE_OBJECT = 'object';
    const DATATYPE_ARRAY = 'array';
    const DATATYPE_BOOLEAN = 'boolean';
    const DATATYPE_STRING = 'string';
    const DATATYPE_NUMBER = 'number';
    const DATATYPE_NULL = 'null';

    /**
     * @var bool
     */
    private $prettyPrint;

    /**
     * @var string
     */
    private $contentType;

    public function __construct(bool $prettyPrint = false, string $contentType = 'application/x-jsonx')
    {
        $this->prettyPrint = $prettyPrint;

        if ('application/x-jsonx' === $contentType) {
            @trigger_error(
                'Use "application/jsonx+xml" instead of "application/x-jsonx", cause jsonx is a xml variant.',
                E_USER_DEPRECATED
            );
        }

        $this->contentType = $contentType;
    }

    public function getContentType(): string
    {
        return $this->contentType;
    }

    public function encode(array $data): string
    {
        $document = new \DOMDocument('1.0', 'UTF-8');
        $document->formatOutput = $this->prettyPrint;

        if (self::DATATYPE_OBJECT === $this->getType($data)) {
            $rootNode = $this->createObjectNode($document, $data);
        } else {
            $rootNode = $this->createArrayNode($document, $data);
        }

        $rootNode->setAttribute('xsi:schemaLocation', 'http://www.datapower.com/schemas/json jsonx.xsd');
        $rootNode->setAttribute('xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
        $rootNode->setAttribute('xmlns:json', 'http://www.ibm.com/xmlns/prod/2009/jsonx');

        $document->appendChild($rootNode);

        return trim($document->saveXML($document));
    }

    /**
     * @param string $name
     */
    private function createObjectNode(\DOMDocument $document, array $value, string $name = null): \DOMNode
    {
        $node = $document->createElement('json:object');

        if (null !== $name) {
            $node->setAttribute('name', $name);
        }

        foreach ($value as $subName => $subValue) {
            $subValueType = $this->getType($subValue);
            if (self::DATATYPE_OBJECT === $subValueType) {
                $subNode = $this->createObjectNode($document, $subValue, (string) $subName);
            } elseif (self::DATATYPE_ARRAY === $subValueType) {
                $subNode = $this->createArrayNode($document, $subValue, (string) $subName);
            } elseif (self::DATATYPE_BOOLEAN === $subValueType) {
                $subNode = $this->createBooleanNode($document, $subValue, (string) $subName);
            } elseif (self::DATATYPE_STRING === $subValueType) {
                $subNode = $this->createStringNode($document, $subValue, (string) $subName);
            } elseif (self::DATATYPE_NUMBER === $subValueType) {
                $subNode = $this->createNumberNode($document, $subValue, (string) $subName);
            } else {
                $subNode = $this->createNullNode($document, (string) $subName);
            }

            $node->appendChild($subNode);
        }

        return $node;
    }

    /**
     * @param string $name
     */
    private function createArrayNode(\DOMDocument $document, array $value, string $name = null): \DOMNode
    {
        $node = $document->createElement('json:array');

        if (null !== $name) {
            $node->setAttribute('name', $name);
        }

        foreach ($value as $subValue) {
            $subValueType = $this->getType($subValue);
            if (self::DATATYPE_OBJECT === $subValueType) {
                $subNode = $this->createObjectNode($document, $subValue);
            } elseif (self::DATATYPE_ARRAY === $subValueType) {
                $subNode = $this->createArrayNode($document, $subValue);
            } elseif (self::DATATYPE_BOOLEAN === $subValueType) {
                $subNode = $this->createBooleanNode($document, $subValue);
            } elseif (self::DATATYPE_STRING === $subValueType) {
                $subNode = $this->createStringNode($document, $subValue);
            } elseif (self::DATATYPE_NUMBER === $subValueType) {
                $subNode = $this->createNumberNode($document, $subValue);
            } else {
                $subNode = $this->createNullNode($document);
            }

            $node->appendChild($subNode);
        }

        return $node;
    }

    /**
     * @param string $name
     */
    private function createBooleanNode(\DOMDocument $document, bool $value, string $name = null): \DOMNode
    {
        $node = $document->createElement('json:boolean', $value ? 'true' : 'false');

        if (null !== $name) {
            $node->setAttribute('name', $name);
        }

        return $node;
    }

    /**
     * @param string $name
     */
    private function createStringNode(\DOMDocument $document, string $value, string $name = null): \DOMNode
    {
        $node = $document->createElement('json:string', htmlentities($value, ENT_COMPAT | ENT_XML1, 'UTF-8'));

        if (null !== $name) {
            $node->setAttribute('name', $name);
        }

        return $node;
    }

    /**
     * @param int|float $value
     * @param string    $name
     */
    private function createNumberNode(\DOMDocument $document, $value, string $name = null): \DOMNode
    {
        $node = $document->createElement('json:number', (string) $value);

        if (null !== $name) {
            $node->setAttribute('name', $name);
        }

        return $node;
    }

    /**
     * @param string $name
     */
    private function createNullNode(\DOMDocument $document, string $name = null): \DOMNode
    {
        $node = $document->createElement('json:null');

        if (null !== $name) {
            $node->setAttribute('name', $name);
        }

        return $node;
    }

    /**
     * @param array|bool|string|int|float|null $value
     */
    private function getType($value): string
    {
        if (is_array($value)) {
            if ($value !== array_values($value)) {
                return self::DATATYPE_OBJECT;
            }

            return self::DATATYPE_ARRAY;
        }

        if (is_bool($value)) {
            return self::DATATYPE_BOOLEAN;
        }

        if (is_string($value)) {
            return self::DATATYPE_STRING;
        }

        if (is_int($value) || is_float($value)) {
            return self::DATATYPE_NUMBER;
        }

        if (null === $value) {
            return self::DATATYPE_NULL;
        }

        throw new \InvalidArgumentException(
            sprintf(
                'Value needs to be of type array|bool|string|int|float|null, %s given',
                is_object($value) ? get_class($value) : gettype($value)
            )
        );
    }
}
