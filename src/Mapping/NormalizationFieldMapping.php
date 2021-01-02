<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Mapping;

use Chubbyphp\Serialization\Normalizer\FieldNormalizerInterface;
use Chubbyphp\Serialization\Policy\NullPolicy;
use Chubbyphp\Serialization\Policy\PolicyInterface;

final class NormalizationFieldMapping implements NormalizationFieldMappingInterface
{
    private string $name;

    private FieldNormalizerInterface $fieldNormalizer;

    private PolicyInterface $policy;

    public function __construct(
        string $name,
        FieldNormalizerInterface $fieldNormalizer,
        ?PolicyInterface $policy = null
    ) {
        $this->name = $name;
        $this->fieldNormalizer = $fieldNormalizer;
        $this->policy = $policy ?? new NullPolicy();
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getFieldNormalizer(): FieldNormalizerInterface
    {
        return $this->fieldNormalizer;
    }

    public function getPolicy(): PolicyInterface
    {
        return $this->policy;
    }
}
