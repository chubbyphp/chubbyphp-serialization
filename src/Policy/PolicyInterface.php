<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Policy;

use Chubbyphp\Serialization\Normalizer\NormalizerContextInterface;

/**
 * @method bool isCompliantIncludingPath(string $path, object $object, NormalizerContextInterface $context)
 */
interface PolicyInterface
{
    /**
     * @deprecated
     */
    public function isCompliant(NormalizerContextInterface $context, object $object): bool;

    //public function isCompliantIncludingPath(string $path, object $object, NormalizerContextInterface $context): bool;
}
