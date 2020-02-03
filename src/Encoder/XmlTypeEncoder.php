<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Encoder;

use Doctrine\Common\Inflector\Inflector;

/**
 * @deprecated use JsonxTypeEncoder instead
 */
final class XmlTypeEncoder implements TypeEncoderInterface
{
    /**
     * @var bool
     */
    private $prettyPrint;

    public function __construct(bool $prettyPrint = false)
    {
        $this->prettyPrint = $prettyPrint;
    }

    public function getContentType(): string
    {
        return 'application/xml';
    }

    /**
     * @param array<mixed> $data
     */
    public function encode(array $data): string
    {
        $document = new \DOMDocument('1.0', 'UTF-8');
        $document->formatOutput = $this->prettyPrint;

        $listNode = $this->createMetadataNode($document, $data['_type'] ?? null);

        unset($data['_type']);

        $document->appendChild($listNode);

        $this->dataToNodes($document, $listNode, $data);

        return trim($document->saveXML($document, LIBXML_NOEMPTYTAG));
    }

    private function createMetadataNode(\DOMDocument $document, ?string $type = null): \DOMNode
    {
        $node = $document->createElement('object');
        if (null !== $type) {
            $node->setAttribute('type', $type);
        }

        return $node;
    }

    /**
     * @return string
     */
    private function getMetaPrefix(string $name)
    {
        return 'meta-'.$name;
    }

    private function dataToNodes(\DOMDocument $document, \DOMNode $listNode, array $data): void
    {
        foreach ($data as $key => $value) {
            if (is_string($key) && '_' === $key[0]) {
                $key = $this->getMetaPrefix(substr($key, 1));
            }

            if (is_array($value)) {
                $childNode = $this->dataToArrayNode($document, $listNode, $key, $value);
            } else {
                $childNode = $this->dataToScalarNode($document, $listNode, $key, $value);
            }

            if (is_int($key)) {
                $childNode->setAttribute('key', (string) $key);
            }

            $listNode->appendChild($childNode);
        }
    }

    /**
     * @param string|int $key
     *
     * @return \DOMElement|\DOMNode
     */
    private function dataToArrayNode(\DOMDocument $document, \DOMNode $listNode, $key, array $value)
    {
        if (!isset($value['_type'])) {
            $childNode = $document->createElement(is_int($key) ? Inflector::singularize($listNode->nodeName) : $key);
            $this->dataToNodes($document, $childNode, $value);

            return $childNode;
        }

        if (is_int($key)) {
            $childNode = $this->createMetadataNode($document, $value['_type']);

            unset($value['_type']);

            $this->dataToNodes($document, $childNode, $value);

            return $childNode;
        }

        $subChildNode = $this->createMetadataNode($document, $value['_type']);

        unset($value['_type']);

        $childNode = $document->createElement($key);
        $childNode->appendChild($subChildNode);

        $this->dataToNodes($document, $subChildNode, $value);

        return $childNode;
    }

    /**
     * @param string|int                 $key
     * @param string|bool|float|int|null $value
     */
    private function dataToScalarNode(\DOMDocument $document, \DOMNode $listNode, $key, $value): \DOMNode
    {
        $stringKey = is_int($key) ? Inflector::singularize($listNode->nodeName) : $key;

        if (is_string($value)) {
            if (false !== strpos($value, '<') || false !== strpos($value, '&')) {
                $childNode = $document->createElement($stringKey);
                $childNode->appendChild($document->createCDATASection($value));
            } else {
                $childNode = $document->createElement($stringKey, $value);
            }

            $childNode->setAttribute('type', 'string');

            return $childNode;
        }

        if (null === $value) {
            return $document->createElement($stringKey);
        }

        $childNode = $document->createElement($stringKey, $this->getValueAsString($value));
        $childNode->setAttribute('type', $this->getType($value));

        return $childNode;
    }

    /**
     * @param bool|float|int $value
     *
     * @throws \InvalidArgumentException
     */
    private function getValueAsString($value): string
    {
        if (is_bool($value)) {
            return $value ? 'true' : 'false';
        }

        if (is_float($value)) {
            $value = (string) $value;
            if (false === strpos($value, '.')) {
                $value .= '.0';
            }

            return $value;
        }

        if (is_int($value)) {
            return (string) $value;
        }

        throw new \InvalidArgumentException(sprintf('Unsupported data type: %s', gettype($value)));
    }

    /**
     * @param bool|float|int|string $value
     */
    private function getType($value): string
    {
        if (is_bool($value)) {
            return 'boolean';
        }

        if (is_float($value)) {
            return 'float';
        }

        return 'integer';
    }
}
