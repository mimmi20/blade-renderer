<?php

declare(strict_types = 1);

namespace Mimmi20\Mezzio\BladeRenderer;

use Illuminate\Contracts\View\Factory as FactoryInterface;
use Illuminate\Events\Dispatcher;
use Illuminate\Filesystem\Filesystem;
use Illuminate\View\Compilers\BladeCompiler;
use Illuminate\View\Engines\EngineResolver;
use Illuminate\View\FileViewFinder;
use Jenssegers\Blade\Blade;
use Mimmi20\Mezzio\BladeRenderer\Renderer\BladeCompilerFactory;
use Mimmi20\Mezzio\BladeRenderer\Renderer\BladeDispatcherFactory;
use Mimmi20\Mezzio\BladeRenderer\Renderer\BladeFactory;
use Mimmi20\Mezzio\BladeRenderer\Renderer\BladeFilesystemFactory;
use Mimmi20\Mezzio\BladeRenderer\Renderer\BladeFileViewFinderFactory;
use Mimmi20\Mezzio\BladeRenderer\Renderer\BladeRenderer;
use Mimmi20\Mezzio\BladeRenderer\Renderer\BladeRendererFactory;
use Mimmi20\Mezzio\BladeRenderer\Renderer\ViewFactoryFactory;
use Mimmi20\Mezzio\BladeRenderer\Resolver\BladeEngineResolverBladeClosureFactory;
use Mimmi20\Mezzio\BladeRenderer\Resolver\BladeEngineResolverFactory;
use Mimmi20\Mezzio\BladeRenderer\Strategy\BladeStrategy;
use Mimmi20\Mezzio\BladeRenderer\Strategy\BladeStrategyFactory;

final class ConfigProvider
{
    /**
     * @return array{dependencies: array{aliases: array<string, class-string>, factories: array<string|class-string, class-string>}, view_manager: array{default_template_suffix: string, strategies: array<int, string|class-string>}, blade: array{cache_dir: string}}
     *
     * @throws void
     */
    public function __invoke(): array
    {
        return [
            'dependencies' => $this->getDependencies(),
            'view_manager' => $this->getViewManagerConfig(),
            'blade' => [
                'cache_dir' => '',
            ],
        ];
    }

    /**
     * @return array{aliases: array<string, class-string>, factories: array<string|class-string, class-string>}
     *
     * @throws void
     */
    public function getDependencies(): array
    {
        return [
            'aliases' => [
                'renderer.blade' => BladeRenderer::class,
                'blade.filesystem' => Filesystem::class,
                'blade.dispatcher' => Dispatcher::class,
                'blade.compiler' => BladeCompiler::class,
                'view.engine.resolver' => EngineResolver::class,
                'blade.file_view_finder' => FileViewFinder::class,

                'blade' => Blade::class,
            ],
            'factories' => [
                BladeRenderer::class => BladeRendererFactory::class,
                BladeStrategy::class => BladeStrategyFactory::class,
                Filesystem::class => BladeFilesystemFactory::class,
                BladeCompiler::class => BladeCompilerFactory::class,
                Dispatcher::class => BladeDispatcherFactory::class,
                EngineResolver::class => BladeEngineResolverFactory::class,
                FileViewFinder::class => BladeFileViewFinderFactory::class,
                /* php closure not used when using blade for rendering but could be used this way
                'blade.engine_resolver_php_closure' => BladeEngineResolverPHPClosureFactory::class,
                */
                'blade.engine_resolver_blade_closure' => BladeEngineResolverBladeClosureFactory::class,
                FactoryInterface::class => ViewFactoryFactory::class,
                Blade::class => BladeFactory::class,
            ],
        ];
    }

    /**
     * @return array{default_template_suffix: string, strategies: array<int, string|class-string>}
     *
     * @throws void
     */
    public function getViewManagerConfig(): array
    {
        return [
            'default_template_suffix' => 'blade.php',

            /**
             * Register the view strategy with the view manager. This is required!
             */
            'strategies' => [
                BladeStrategy::class,
            ],
        ];
    }
}
