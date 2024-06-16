<?php

declare(strict_types = 1);

namespace Mimmi20\Mezzio\BladeRenderer\Renderer;

use Illuminate\Events\Dispatcher;
use Psr\Container\ContainerInterface;

final class BladeDispatcherFactory
{
    /**
     * @throws void
     *
     * @phpcsSuppress SlevomatCodingStandard.Functions.UnusedParameter.UnusedParameter
     */
    public function __invoke(ContainerInterface $container): Dispatcher
    {
        return new Dispatcher();
    }
}
