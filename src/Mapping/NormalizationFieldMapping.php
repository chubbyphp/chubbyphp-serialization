<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Mapping;

use Chubbyphp\Serialization\Normalizer\FieldNormalizerInterface;
use Chubbyphp\Serialization\Policy\PolicyInterface;
use Chubbyphp\Serialization\Policy\NullPolicy;

final class NormalizationFieldMapping implements NormalizationFieldMappingInterface
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var array
     */
    private $groups;

    /**
     * @var FieldNormalizerInterface
     */
    private $fieldNormalizer;

    /**
     * @var PolicyInterface
     */
    private $policy;

    /**
     * @param string                   $name
     * @param array                    $groups
     * @param FieldNormalizerInterface $fieldNormalizer
     * @param PolicyInterface|null     $policy
     */
    public function __construct(
        $name,
        array $groups,
        FieldNormalizerInterface $fieldNormalizer,
        PolicyInterface $policy = null
    ) {
        $this->name = $name;
        $this->groups = $groups;
        $this->fieldNormalizer = $fieldNormalizer;
        $this->policy = $policy ?? new NullPolicy();
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @deprecated
     *
     * @return string[]
     */
    public function getGroups(): array
    {
        return $this->groups;
    }

    /**
     * @return FieldNormalizerInterface
     */
    public function getFieldNormalizer(): FieldNormalizerInterface
    {
        return $this->fieldNormalizer;
    }

    /**
     * @return PolicyInterface
     */
    public function getPolicy(): PolicyInterface
    {
        return $this->policy;
    }
}
