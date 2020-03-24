<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Policy;

use Chubbyphp\Serialization\Normalizer\NormalizerContextInterface;

/**
 * @method bool isCompliantIncludingPath(object $object, NormalizerContextInterface $context, string $path)
 */
interface PolicyInterface
{
    public function isCompliant(NormalizerContextInterface $context, object $object): bool;

    //public function isCompliantIncludingPath(object $object, NormalizerContextInterface $context, string $path): bool;
}
