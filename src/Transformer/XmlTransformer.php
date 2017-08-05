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
        $document = new \DOMDocument('1.0', 'UTF-8');
        $document->formatOutput = $this->formatOutput;

        $responseNode = $document->createElement('response');

        $document->appendChild($responseNode);

        $this->dataToNodes($document, $responseNode, $data);

        return trim($document->saveXML(null, $this->options));
    }

    /**
     * @param \DOMDocument $document
     * @param \DOMNode     $listNode
     * @param array        $data
     */
    private function dataToNodes(\DOMDocument $document, \DOMNode $listNode, array $data)
    {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $childNode = $document->createElement(is_int($key) ? Inflector::singularize($listNode->nodeName) : $key);
                $this->dataToNodes($document, $childNode, $value);
            } else {
                if (is_string($value)) {
                    $childNode = $document->createElement($key);
                    $childNode->appendChild($document->createCDATASection($value));
                    $childNode->setAttribute('type', 'string');
                } elseif (null === $value) {
                    $childNode = $document->createElement($key);
                } else {
                    $childNode = $document->createElement($key, $this->getValueAsString($value));
                    $childNode->setAttribute('type', $this->getType($value));
                }
            }

            $listNode->appendChild($childNode);
        }
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
