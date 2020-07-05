<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Policy;

use Chubbyphp\Serialization\Normalizer\NormalizerContextInterface;

interface PolicyInterface
{
    public function isCompliantIncludingPath(string $path, object $object, NormalizerContextInterface $context): bool;
}
