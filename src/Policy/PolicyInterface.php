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
     *
     * @param object|mixed $object
     */
    public function isCompliant(NormalizerContextInterface $context, $object): bool;

    //public function isCompliantIncludingPath(string $path, object $object, NormalizerContextInterface $context): bool;
}
