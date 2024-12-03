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

use Laminas\ModuleManager\Feature\ConfigProviderInterface;

final class Module implements ConfigProviderInterface
{
    /**
     * @return array{dependencies: array{aliases: array<string, class-string>, factories: array<class-string|string, class-string>}, blade: array{cache_dir: string}}
     *
     * @throws void
     */
    public function getConfig(): array
    {
        $configProvider = new ConfigProvider();

        return $configProvider();
    }
}
