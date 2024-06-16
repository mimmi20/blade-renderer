<?php

declare(strict_types = 1);

namespace Mimmi20\Mezzio\BladeRenderer\Resolver;

use Closure;
use Illuminate\Filesystem\Filesystem;
use Illuminate\View\Compilers\BladeCompiler;
use Illuminate\View\Engines\CompilerEngine;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

final class BladeEngineResolverBladeClosureFactory
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): Closure
    {
        $filesystem    = $container->get(Filesystem::class);
        $bladeCompiler = $container->get(BladeCompiler::class);

        return static fn () => new CompilerEngine($bladeCompiler, $filesystem);
    }
}
