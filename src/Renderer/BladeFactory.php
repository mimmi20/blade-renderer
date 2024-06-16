<?php

declare(strict_types = 1);

namespace Mimmi20\Mezzio\BladeRenderer\Renderer;

use Jenssegers\Blade\Blade;
use Laminas\ServiceManager\Exception\ServiceNotCreatedException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

final class BladeFactory
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): Blade
    {
        $config = $container->get('config');
        $viewManagerConfig = [];

        if (is_array($config) && array_key_exists('view_manager', $config) && is_array($config['view_manager'])) {
            $viewManagerConfig = $config['view_manager'];
        }

        $cacheDir = null;

        if (is_array($config) && array_key_exists('blade', $config) && is_array($config['blade']) && array_key_exists('cache_dir', $config['blade']) && is_string($config['blade']['cache_dir'])) {
            $cacheDir = $config['blade']['cache_dir'];
        }

        if ($cacheDir === null) {
            throw new ServiceNotCreatedException(
                sprintf('Could not create service %s, a cache directory is required', Blade::class)
            );
        }

        return new Blade(
            viewPaths: $viewManagerConfig['template_path_stack'] ?? [],
            cachePath: $cacheDir,
        );
    }
}
