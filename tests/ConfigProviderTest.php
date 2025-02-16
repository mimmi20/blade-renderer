<?php

/**
 * This file is part of the mimmi20/blade-renderer package.
 *
 * Copyright (c) 2024-2025, Thomas Mueller <mimmi20@live.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Mimmi20\Mezzio\BladeRenderer;

use Mimmi20\Mezzio\BladeRenderer\Renderer\BladeRenderer;
use Mimmi20\Mezzio\BladeRenderer\Renderer\Container;
use Mimmi20\Mezzio\BladeRenderer\Strategy\BladeStrategy;
use Override;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\TestCase;

final class ConfigProviderTest extends TestCase
{
    private ConfigProvider $provider;

    /** @throws void */
    #[Override]
    protected function setUp(): void
    {
        $this->provider = new ConfigProvider();
    }

    /** @throws Exception */
    public function testGetDependencies(): void
    {
        $dependencies = $this->provider->getDependencies();
        self::assertIsArray($dependencies);

        self::assertArrayHasKey('factories', $dependencies);
        $factories = $dependencies['factories'];
        self::assertIsArray($factories);
        self::assertArrayHasKey(BladeRenderer::class, $factories);
        self::assertArrayHasKey(BladeStrategy::class, $factories);
        self::assertArrayHasKey(Container::class, $factories);

        self::assertArrayHasKey('aliases', $dependencies);
        $aliases = $dependencies['aliases'];
        self::assertIsArray($aliases);
        self::assertArrayHasKey('renderer.blade', $aliases);
    }

    /** @throws Exception */
    public function testGetViewManagerConfig(): void
    {
        $viewManagerConfig = $this->provider->getViewManagerConfig();
        self::assertIsArray($viewManagerConfig);

        self::assertArrayHasKey('default_template_suffix', $viewManagerConfig);
        self::assertArrayHasKey('strategies', $viewManagerConfig);

        self::assertSame('blade.php', $viewManagerConfig['default_template_suffix']);

        $strategies = $viewManagerConfig['strategies'];
        self::assertIsArray($strategies);
        self::assertSame([
            BladeStrategy::class,
        ], $strategies);
    }

    /** @throws Exception */
    public function testInvocationReturnsArrayWithDependencies(): void
    {
        $config = ($this->provider)();

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
        self::assertArrayHasKey(Container::class, $factories);

        self::assertArrayHasKey('aliases', $dependencies);
        $aliases = $dependencies['aliases'];
        self::assertIsArray($aliases);
        self::assertArrayHasKey('renderer.blade', $aliases);

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
