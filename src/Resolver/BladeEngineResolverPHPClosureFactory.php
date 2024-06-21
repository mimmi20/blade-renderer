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

namespace Mimmi20\Mezzio\BladeRenderer\Resolver;

use Closure;
use Illuminate\Filesystem\Filesystem;
use Illuminate\View\Engines\PhpEngine;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

final class BladeEngineResolverPHPClosureFactory
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): Closure
    {
        $filesystem = $container->get(Filesystem::class);

        return static fn () => new PhpEngine($filesystem);
    }
}
