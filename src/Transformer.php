<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization;

use Chubbyphp\Serialization\Transformer\TransformerInterface as ContenTypeTransformerInterface;

final class Transformer implements TransformerInterface
{
    /**
     * @var ContenTypeTransformerInterface[]
     */
    private $transformers;

    /**
     * @param ContenTypeTransformerInterface[] $transformers
     */
    public function __construct(array $transformers)
    {
        $this->transformers = [];
        foreach ($transformers as $transformer) {
            $this->addTransformer($transformer);
        }
    }

    /**
     * @param ContenTypeTransformerInterface $transformer
     */
    private function addTransformer(ContenTypeTransformerInterface $transformer)
    {
        $this->transformers[$transformer->getContentType()] = $transformer;
    }

    /**
     * @return array
     */
    public function getContentTypes(): array
    {
        return array_keys($this->transformers);
    }

    /**
     * @param array  $data
     * @param string $contentType
     *
     * @return string
     *
     * @throws \InvalidArgumentException
     */
    public function transform(array $data, string $contentType): string
    {
        if (isset($this->transformers[$contentType])) {
            return $this->transformers[$contentType]->transform($data);
        }

        throw new \InvalidArgumentException(sprintf('There is no transformer for content-type: %s', $contentType));
    }
}
