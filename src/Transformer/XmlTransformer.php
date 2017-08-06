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

        $listNode = $document->createElement($this->getMetaPrefix('type', $data['_type']));
        $document->appendChild($listNode);

        unset($data['_type']);

        $this->dataToNodes($document, $listNode, $data);

        return trim($document->saveXML(null, $this->options));
    }

    /**
     * @param string      $name
     * @param string|null $value
     *
     * @return string
     */
    private function getMetaPrefix(string $name, string $value = null)
    {
        if (null === $value) {
            return 'meta-'.$name;
        }

        return 'meta-'.$name.'--'.$value;
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
                if (isset($value['_type'])) {
                    $childNode = $document->createElement($this->getMetaPrefix('type', $value['_type']));
                    unset($value['_type']);
                } else {
                    $childNode = $document->createElement(
                        is_int($key) ? Inflector::singularize($listNode->nodeName) : $key
                    );
                }

                $this->dataToNodes($document, $childNode, $value);
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

        if (is_int($value)) {
            return 'integer';
        }

        if (is_string($value)) {
            return 'string';
        }

        throw new \InvalidArgumentException(sprintf('Unsupported data type: %s', gettype($value)));
    }
}
