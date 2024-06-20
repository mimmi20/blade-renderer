<?php
/**
 * This file is part of the mimmi20/blade-renderer package.
 *
 * Copyright (c) 2024, Thomas Mueller <mimmi20@live.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Mimmi20\Mezzio\BladeRenderer\Renderer;

use Illuminate\Contracts\Foundation\Application as ApplicationContract;
use Illuminate\Contracts\View\Factory as FactoryContract;
use Jenssegers\Blade\Blade;
use Laminas\ServiceManager\Exception\ServiceNotCreatedException;
use LogicException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

use function array_key_exists;
use function is_array;
use function is_int;
use function is_string;
use function sprintf;

final class BladeFactory
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws ServiceNotCreatedException
     * @throws LogicException
     */
    public function __invoke(ContainerInterface $container): Blade
    {
        $config            = $container->get('config');
        $viewManagerConfig = [];

        if (
            is_array($config) && array_key_exists('view_manager', $config) && is_array(
                $config['view_manager'],
            )
        ) {
            $viewManagerConfig = $config['view_manager'];
        }

        $cacheDir = null;

        if (
            is_array($config)
            && array_key_exists('blade', $config)
            && is_array($config['blade'])
            && array_key_exists('cache_dir', $config['blade'])
            && is_string($config['blade']['cache_dir'])
        ) {
            $cacheDir = $config['blade']['cache_dir'];
        }

        if ($cacheDir === null) {
            throw new ServiceNotCreatedException(
                sprintf('Could not create service %s, a cache directory is required', Blade::class),
            );
        }

        $app = Container::getInstance();
        $app->bind(ApplicationContract::class, Container::class);
        $app->alias('view', FactoryContract::class);

        $blade = new Blade(
            viewPaths: $viewManagerConfig['template_path_stack'] ?? [],
            cachePath: $cacheDir,
            container: $app,
        );

        if (
            is_array($config)
            && array_key_exists('blade', $config)
            && is_array($config['blade'])
            && array_key_exists('components', $config['blade'])
            && is_array($config['blade']['components'])
        ) {
            foreach ($config['blade']['components'] as $alias => $classOrTemplate) {
                if (!is_string($classOrTemplate)) {
                    continue;
                }

                if (is_int($alias)) {
                    $blade->compiler()->component($classOrTemplate);

                    continue;
                }

                $blade->compiler()->component($classOrTemplate, $alias);
            }
        }

        return $blade;
    }
}