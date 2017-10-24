<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Normalizer;

use Psr\Http\Message\ServerRequestInterface;

final class NormalizerContext implements NormalizerContextInterface
{
    /**
     * @var string[]
     */
    private $groups = [];

    /**
     * @var ServerRequestInterface|null
     */
    private $request;

    /**
     * @param string[]                    $groups
     * @param ServerRequestInterface|null $request
     */
    public function __construct(array $groups = [], ServerRequestInterface $request = null)
    {
        $this->groups = $groups;
        $this->request = $request;
    }

    /**
     * @return string[]
     */
    public function getGroups(): array
    {
        return $this->groups;
    }

    /**
     * @return ServerRequestInterface|null
     */
    public function getRequest()
    {
        return $this->request;
    }
}
