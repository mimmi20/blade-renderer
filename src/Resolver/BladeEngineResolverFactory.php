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
