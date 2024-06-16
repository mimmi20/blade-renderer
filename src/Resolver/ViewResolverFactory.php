<?php

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
