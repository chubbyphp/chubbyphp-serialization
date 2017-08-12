<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Transformer;

use Doctrine\Common\Inflector\Inflector;

final class XmlTransformer implements TransformerInterface
{
    /**
     * @var bool
     */
    private $formatOutput;

    /**
     * @var int
     */
    private $options;

    /**
     * @param bool $formatOutput
     * @param int  $options
     */
    public function __construct(bool $formatOutput = false, int $options = LIBXML_NOEMPTYTAG)
    {
        $this->formatOutput = $formatOutput;
        $this->options = $options;
    }

    /**
     * @return string
     */
    public function getContentType(): string
    {
        return 'application/xml';
    }

    /**
     * @param array $data
     *
     * @return string
     */
    public function transform(array $data): string
    {
        if (!isset($data['_type'])) {
            throw new \InvalidArgumentException('Data missing _type');
        }

        $document = new \DOMDocument('1.0', 'UTF-8');
        $document->formatOutput = $this->formatOutput;

        $listNode = $this->createMetadataNode($document, 'type', $data['_type']);
        $document->appendChild($listNode);

        unset($data['_type']);

        $this->dataToNodes($document, $listNode, $data);

        return trim($document->saveXML(null, $this->options));
    }

    /**
     * @param \DOMDocument $document
     * @param string       $name
     * @param string|null  $value
     *
     * @return \DOMNode
     */
    private function createMetadataNode(\DOMDocument $document, string $name, string $value = null): \DOMNode
    {
        $node = $document->createElement($this->getMetaPrefix($name));
        if (null !== $value) {
            $node->setAttribute('value', $value);
        }

        return $node;
    }

    /**
     * @param string $name
     *
     * @return string
     */
    private function getMetaPrefix(string $name)
    {
        return 'meta-'.$name;
    }

    /**
     * @param \DOMDocument $document
     * @param \DOMNode     $listNode
     * @param array        $data
     */
    private function dataToNodes(\DOMDocument $document, \DOMNode $listNode, array $data)
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
     * @param \DOMDocument $document
     * @param \DOMNode     $listNode
     * @param string|int   $key
     * @param array        $value
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
            $childNode = $this->createMetadataNode($document, 'type', $value['_type']);

            unset($value['_type']);

            $this->dataToNodes($document, $childNode, $value);

            return $childNode;
        }

        $subChildNode = $this->createMetadataNode($document, 'type', $value['_type']);

        unset($value['_type']);

        $childNode = $document->createElement($key);
        $childNode->appendChild($subChildNode);

        $this->dataToNodes($document, $subChildNode, $value);

        return $childNode;
    }

    /**
     * @param \DOMDocument               $document
     * @param \DOMNode                   $listNode
     * @param string|int                 $key
     * @param string|null|bool|float|int $value
     *
     * @return \DOMNode
     */
    private function dataToScalarNode(\DOMDocument $document, \DOMNode $listNode, $key, $value): \DOMNode
    {
        $stringKey = is_int($key) ? Inflector::singularize($listNode->nodeName) : $key;

        if (is_string($value)) {
            if (strpos($value, '<') !== false || strpos($value, '&') !== false) {
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
     * @return string|null
     *
     * @throws \InvalidArgumentException
     */
    private function getValueAsString($value): string
    {
        if (is_bool($value)) {
            return $value ? 'true' : 'false';
        }

        if (is_float($value) || is_int($value)) {
            return (string) $value;
        }

        throw new \InvalidArgumentException(sprintf('Unsupported data type: %s', gettype($value)));
    }

    /**
     * @param bool|float|int|string $value
     *
     * @return null|string
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
