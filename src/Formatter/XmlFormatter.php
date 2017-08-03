<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Formatter;

use Doctrine\Common\Inflector\Inflector;

final class XmlFormatter implements FormatterInterface
{
    /**
     * @var bool
     */
    private $formatOutput;

    /**
     * @param bool $formatOutput
     */
    public function __construct(bool $formatOutput = false)
    {
        $this->formatOutput = $formatOutput;
    }

    /**
     * @param array $data
     *
     * @return string
     */
    public function format(array $data): string
    {
        $document = new \DOMDocument('1.0', 'UTF-8');
        $document->formatOutput = $this->formatOutput;

        $this->dataToNodes($document, $document, $data);

        return $document->saveXML();
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
                $childNode = $document->createElement($key, $this->getValueAsString($value));
                $childNode->setAttribute('type', gettype($value));
            }

            $listNode->appendChild($childNode);
        }
    }

    /**
     * @param bool|float|int|string $value
     * @return string
     */
    private function getValueAsString($value): string
    {
        $type = gettype($value);

        if ('boolean' === $type) {
            return $value ? 'true' : 'false';
        }

        return (string) $value;
    }
}
