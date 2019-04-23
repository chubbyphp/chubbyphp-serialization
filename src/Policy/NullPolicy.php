<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Policy;

use Chubbyphp\Serialization\Normalizer\NormalizerContextInterface;

final class NullPolicy implements PolicyInterface
{
    /**
     * @param NormalizerContextInterface $context
     * @param object                     $object
     *
     * @return bool
     */
    public function isCompliant(NormalizerContextInterface $context, $object): bool
    {
        return true;
    }
}
