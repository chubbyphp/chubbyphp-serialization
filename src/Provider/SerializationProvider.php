<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Provider;

use Chubbyphp\Serialization\ServiceProvider\SerializationServiceProvider;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

final class SerializationProvider implements ServiceProviderInterface
{
    /**
     * @var SerializationServiceProvider
     */
    private $serviceProvider;

    public function __construct()
    {
        $this->serviceProvider = new SerializationServiceProvider();
    }

    public function register(Container $container): void
    {
        $this->serviceProvider->register($container);
    }
}
