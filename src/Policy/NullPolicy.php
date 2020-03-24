<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Policy;

use Chubbyphp\Serialization\Normalizer\NormalizerContextInterface;

final class NullPolicy implements PolicyInterface
{
    public function isCompliant(NormalizerContextInterface $context, object $object): bool
    {
        return true;
    }

    public function isCompliantIncludingPath(object $object, NormalizerContextInterface $context, string $path): bool
    {
        return true;
    }
}
