<?php

declare(strict_types = 1);

namespace Mimmi20\Mezzio\BladeRenderer\Renderer;

use Illuminate\Filesystem\Filesystem;
use Illuminate\View\FileViewFinder;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

final class BladeFileViewFinderFactory
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): FileViewFinder
    {
        $config = $container->get('config');
        $viewManagerConfig = [];

        if (is_array($config) && array_key_exists('view_manager', $config) && is_array($config['view_manager'])) {
            $viewManagerConfig = $config['view_manager'];
        }

        $paths = [];

        if (array_key_exists('template_path_stack', $viewManagerConfig) && is_array($viewManagerConfig['template_path_stack'])) {
            $paths = $viewManagerConfig['template_path_stack'];
        }

        return new FileViewFinder(
            files: $container->get(Filesystem::class),
            paths: $paths,
        );
    }
}
