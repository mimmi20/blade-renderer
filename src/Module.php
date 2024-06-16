<?php

declare(strict_types = 1);

namespace Mimmi20\Mezzio\BladeRenderer;

use Laminas\ModuleManager\Feature\ConfigProviderInterface;
use Laminas\ModuleManager\Feature\ServiceProviderInterface;

final class Module implements ConfigProviderInterface, ServiceProviderInterface
{
    /**
     * @return array{dependencies: array{aliases: array<string, class-string>, factories: array<string|class-string, class-string>}, blade: array{cache_dir: string}}
     *
     * @throws void
     */
    public function getConfig(): array
    {
        $configProvider = new ConfigProvider();

        return $configProvider();
    }

    /**
     * @return array{aliases: array<string, class-string>, factories: array<string|class-string, class-string>}
     *
     * @throws void
     */
    public function getServiceConfig(): array
    {
        $configProvider = new ConfigProvider();

        return $configProvider->getDependencies();
    }
}
