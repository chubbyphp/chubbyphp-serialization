<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Provider;

use Chubbyphp\Serialization\ServiceProvider\SerializationServiceProvider;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

/**
 * @deprecated use \Chubbyphp\Serialization\ServiceProvider\SerializationServiceProvider
 */
final class SerializationProvider implements ServiceProviderInterface
{
    /**
     * @var SerializationServiceProvider
     */
    private $serviceProvider;

    public function __construct()
    {
        @trigger_error(
            sprintf('Use "%s" instead.', SerializationServiceProvider::class),
            E_USER_DEPRECATED
        );

        $this->serviceProvider = new SerializationServiceProvider();
    }

    public function register(Container $container): void
    {
        $this->serviceProvider->register($container);
    }
}
