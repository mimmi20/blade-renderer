<?php

declare(strict_types = 1);

namespace Mimmi20\Mezzio\BladeRenderer\Renderer;

use Illuminate\Filesystem\Filesystem;
use Illuminate\View\Compilers\BladeCompiler;
use Laminas\ServiceManager\Exception\ServiceNotCreatedException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

final class BladeCompilerFactory
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws \InvalidArgumentException
     */
    public function __invoke(ContainerInterface $container): BladeCompiler
    {
        $config = $container->get('config');
        $cacheDir = null;

        if (is_array($config) && array_key_exists('blade', $config) && is_array($config['blade']) && array_key_exists('cache_dir', $config['blade']) && is_string($config['blade']['cache_dir'])) {
            $cacheDir = $config['blade']['cache_dir'];
        }

        if ($cacheDir === null) {
            throw new ServiceNotCreatedException(
                sprintf('Could not create service %s, a cache directory is required', BladeCompiler::class)
            );
        }

        return new BladeCompiler(
            $container->get(Filesystem::class),
            $cacheDir,
        );
    }
}
