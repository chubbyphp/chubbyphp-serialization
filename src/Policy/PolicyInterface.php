<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Policy;

use Chubbyphp\Serialization\Normalizer\NormalizerContextInterface;

interface PolicyInterface
{
    /**
     * @param object|mixed $object
     */
    public function isCompliant(NormalizerContextInterface $context, $object): bool;
}
