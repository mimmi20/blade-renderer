<?php

declare(strict_types = 1);

namespace Mimmi20\Mezzio\BladeRenderer\Resolver;

use Illuminate\View\Engines\EngineResolver;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

final class BladeEngineResolverFactory
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): EngineResolver
    {
        $engineResolver = new EngineResolver();

        $engineResolver->register('blade', $container->get('blade.engine_resolver_blade_closure'));
        // php closure not used when using blade for rendering but could replace the above replace this way:
        // $engineResolver->register('blade', $container->get('blade.engine_resolver_php_closure'));

        return $engineResolver;
    }
}
