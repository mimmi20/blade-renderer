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

namespace Mimmi20\Mezzio\BladeRenderer;

use Illuminate\Contracts\View\Factory as FactoryInterface;
use Illuminate\Events\Dispatcher;
use Illuminate\Filesystem\Filesystem;
use Illuminate\View\Compilers\BladeCompiler;
use Illuminate\View\Engines\EngineResolver;
use Illuminate\View\FileViewFinder;
use Jenssegers\Blade\Blade;
use Mimmi20\Mezzio\BladeRenderer\Renderer\BladeRenderer;
use Mimmi20\Mezzio\BladeRenderer\Strategy\BladeStrategy;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\TestCase;

final class ModuleTest extends TestCase
{
    /** @throws Exception */
    public function testGetConfig(): void
    {
        $module = new Module();

        $config = $module->getConfig();

        self::assertIsArray($config);
        self::assertArrayHasKey('dependencies', $config);
        self::assertArrayHasKey('view_manager', $config);
        self::assertArrayHasKey('blade', $config);

        $dependencies = $config['dependencies'];
        self::assertIsArray($dependencies);

        self::assertArrayHasKey('factories', $dependencies);
        $factories = $dependencies['factories'];
        self::assertIsArray($factories);
        self::assertArrayHasKey(BladeRenderer::class, $factories);
        self::assertArrayHasKey(BladeStrategy::class, $factories);
        self::assertArrayHasKey(Filesystem::class, $factories);
        self::assertArrayHasKey(BladeCompiler::class, $factories);
        self::assertArrayHasKey(Dispatcher::class, $factories);
        self::assertArrayHasKey(EngineResolver::class, $factories);
        self::assertArrayHasKey(FileViewFinder::class, $factories);
        self::assertArrayHasKey('blade.engine_resolver_blade_closure', $factories);
        self::assertArrayHasKey(FactoryInterface::class, $factories);
        self::assertArrayHasKey(Blade::class, $factories);

        self::assertArrayHasKey('aliases', $dependencies);
        $aliases = $dependencies['aliases'];
        self::assertIsArray($aliases);
        self::assertArrayHasKey('renderer.blade', $aliases);
        self::assertArrayHasKey('blade.filesystem', $aliases);
        self::assertArrayHasKey('blade.dispatcher', $aliases);
        self::assertArrayHasKey('blade.compiler', $aliases);
        self::assertArrayHasKey('view.engine.resolver', $aliases);
        self::assertArrayHasKey('blade.file_view_finder', $aliases);
        self::assertArrayHasKey('blade', $aliases);

        $viewManagerConfig = $config['view_manager'];
        self::assertIsArray($viewManagerConfig);

        self::assertArrayHasKey('default_template_suffix', $viewManagerConfig);
        self::assertArrayHasKey('strategies', $viewManagerConfig);

        self::assertSame('blade.php', $viewManagerConfig['default_template_suffix']);

        $strategies = $viewManagerConfig['strategies'];
        self::assertIsArray($strategies);
        self::assertSame([
            BladeStrategy::class,
        ], $strategies);

        $bladeConfig = $config['blade'];
        self::assertIsArray($bladeConfig);
        self::assertSame(['cache_dir' => ''], $bladeConfig);
    }
}
