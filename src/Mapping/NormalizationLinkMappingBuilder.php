<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Mapping;

use Chubbyphp\Serialization\Normalizer\CallbackLinkNormalizer;
use Chubbyphp\Serialization\Normalizer\LinkNormalizer;
use Chubbyphp\Serialization\Normalizer\LinkNormalizerInterface;
use Chubbyphp\Serialization\Policy\NullPolicy;
use Chubbyphp\Serialization\Policy\PolicyInterface;
use Psr\Link\LinkInterface;

final class NormalizationLinkMappingBuilder
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var LinkNormalizerInterface
     */
    private $linkNormalizer;

    /**
     * @var PolicyInterface|null
     */
    private $policy;

    private function __construct(string $name)
    {
        $this->name = $name;
    }

    public static function create(
        string $name,
        LinkNormalizerInterface $linkNormalizer
    ): self {
        $self = new self($name);
        $self->linkNormalizer = $linkNormalizer;

        return $self;
    }

    public static function createCallback(
        string $name,
        callable $callback
    ): self {
        $self = new self($name);
        $self->linkNormalizer = new CallbackLinkNormalizer($callback);

        return $self;
    }

    public static function createLink(
        string $name,
        LinkInterface $link
    ): self {
        $self = new self($name);
        $self->linkNormalizer = new LinkNormalizer($link);

        return $self;
    }

    public function setPolicy(PolicyInterface $policy): self
    {
        $this->policy = $policy;

        return $this;
    }

    public function getMapping(): NormalizationLinkMappingInterface
    {
        return new NormalizationLinkMapping(
            $this->name,
            $this->linkNormalizer,
            $this->policy ?? new NullPolicy()
        );
    }
}
