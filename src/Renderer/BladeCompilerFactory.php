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

use Illuminate\Filesystem\Filesystem;
use Illuminate\View\Compilers\BladeCompiler;
use InvalidArgumentException;
use Laminas\ServiceManager\Exception\ServiceNotCreatedException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

use function array_key_exists;
use function is_array;
use function is_string;
use function sprintf;

final class BladeCompilerFactory
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws InvalidArgumentException
     * @throws ServiceNotCreatedException
     */
    public function __invoke(ContainerInterface $container): BladeCompiler
    {
        $config   = $container->get('config');
        $cacheDir = null;

        if (
            is_array($config) && array_key_exists('blade', $config) && is_array(
                $config['blade'],
            ) && array_key_exists('cache_dir', $config['blade']) && is_string(
                $config['blade']['cache_dir'],
            )
        ) {
            $cacheDir = $config['blade']['cache_dir'];
        }

        if ($cacheDir === null) {
            throw new ServiceNotCreatedException(
                sprintf(
                    'Could not create service %s, a cache directory is required',
                    BladeCompiler::class,
                ),
            );
        }

        return new BladeCompiler(
            $container->get(Filesystem::class),
            $cacheDir,
        );
    }
}
