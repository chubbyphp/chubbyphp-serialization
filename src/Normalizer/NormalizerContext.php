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
     * @var object|string|null
     */
    private $role;

    /**
     * @param string[]                    $groups
     * @param ServerRequestInterface|null $request
     * @param object|string|null          $role
     */
    public function __construct(array $groups = [], ServerRequestInterface $request = null, $role = null)
    {
        $this->groups = $groups;
        $this->request = $request;
        $this->role = $role;
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

    /**
     * @return object|string|null
     */
    public function getRole()
    {
        return $this->role;
    }
}
