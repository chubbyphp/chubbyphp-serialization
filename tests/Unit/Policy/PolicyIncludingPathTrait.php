<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization\Unit\Policy;

use Chubbyphp\Serialization\Normalizer\NormalizerContextInterface;
use Chubbyphp\Serialization\Policy\PolicyInterface;

/**
 * @todo remove as soon isCompliantIncludingPath() is part of the policy interface
 */
trait PolicyIncludingPathTrait
{
    private function getCompliantPolicyIncludingPath(bool $isCompliant): PolicyInterface {
        return new class($isCompliant) implements PolicyInterface {
            private $isPolicyCompliant;

            public function __construct($isPolicyCompliant)
            {
                $this->isPolicyCompliant = $isPolicyCompliant;
            }

            public function isCompliant(NormalizerContextInterface $context, object $object): bool
            {
                return $this->isPolicyCompliant;
            }

            public function isCompliantIncludingPath(
                object $object,
                NormalizerContextInterface $context,
                string $path
            ): bool {
                return $this->isPolicyCompliant;
            }
        };
    }
}
