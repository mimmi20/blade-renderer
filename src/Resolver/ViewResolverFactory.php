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

use Laminas\View\Resolver\AggregateResolver;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

final class ViewResolverFactory
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): AggregateResolver
    {
        $resolver = new AggregateResolver();
        $resolver->attach($container->get('ViewTemplateMapResolver'));
        $resolver->attach($container->get('ViewTemplatePathStack'));

        return $resolver;
    }
}
