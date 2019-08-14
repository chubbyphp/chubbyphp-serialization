<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Mapping;

use Chubbyphp\Serialization\Normalizer\CallbackLinkNormalizer;
use Chubbyphp\Serialization\Normalizer\LinkNormalizer;
use Chubbyphp\Serialization\Normalizer\LinkNormalizerInterface;
use Chubbyphp\Serialization\Policy\NullPolicy;
use Chubbyphp\Serialization\Policy\PolicyInterface;
use Psr\Link\LinkInterface;

final class NormalizationLinkMappingBuilder implements NormalizationLinkMappingBuilderInterface
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var array
     */
    private $groups = [];

    /**
     * @var LinkNormalizerInterface
     */
    private $linkNormalizer;

    /**
     * @var PolicyInterface|null
     */
    private $policy;

    /**
     * @param string $name
     */
    private function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * @param string                  $name
     * @param LinkNormalizerInterface $linkNormalizer
     *
     * @return NormalizationLinkMappingBuilderInterface
     */
    public static function create(
        string $name,
        LinkNormalizerInterface $linkNormalizer
    ): NormalizationLinkMappingBuilderInterface {
        $self = new self($name);
        $self->linkNormalizer = $linkNormalizer;

        return $self;
    }

    /**
     * @param string   $name
     * @param callable $callback
     *
     * @return NormalizationLinkMappingBuilderInterface
     */
    public static function createCallback(
        string $name,
        callable $callback
    ): NormalizationLinkMappingBuilderInterface {
        $self = new self($name);
        $self->linkNormalizer = new CallbackLinkNormalizer($callback);

        return $self;
    }

    /**
     * @param string        $name
     * @param LinkInterface $link
     *
     * @return NormalizationLinkMappingBuilderInterface
     */
    public static function createLink(
        string $name,
        LinkInterface $link
    ): NormalizationLinkMappingBuilderInterface {
        $self = new self($name);
        $self->linkNormalizer = new LinkNormalizer($link);

        return $self;
    }

    /**
     * @deprecated
     *
     * @param array $groups
     *
     * @return NormalizationLinkMappingBuilderInterface
     */
    public function setGroups(array $groups): NormalizationLinkMappingBuilderInterface
    {
        $this->groups = $groups;

        return $this;
    }

    /**
     * @param PolicyInterface $policy
     *
     * @return NormalizationLinkMappingBuilderInterface
     */
    public function setPolicy(PolicyInterface $policy): NormalizationLinkMappingBuilderInterface
    {
        $this->policy = $policy;

        return $this;
    }

    /**
     * @return NormalizationLinkMappingInterface
     */
    public function getMapping(): NormalizationLinkMappingInterface
    {
        return new NormalizationLinkMapping(
            $this->name,
            $this->groups,
            $this->linkNormalizer,
            $this->policy ?? new NullPolicy()
        );
    }
}
