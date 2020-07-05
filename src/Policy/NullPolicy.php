<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Policy;

use Chubbyphp\Serialization\Normalizer\NormalizerContextInterface;

final class NullPolicy implements PolicyInterface
{
    public function isCompliantIncludingPath(string $path, object $object, NormalizerContextInterface $context): bool
    {
        return true;
    }
}
