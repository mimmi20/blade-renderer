<?php

declare(strict_types = 1);

namespace Mimmi20\Mezzio\BladeRenderer\Renderer;

use Illuminate\Filesystem\Filesystem;
use Psr\Container\ContainerInterface;

final class BladeFilesystemFactory
{
    /**
     * @throws void
     *
     * @phpcsSuppress SlevomatCodingStandard.Functions.UnusedParameter.UnusedParameter
     */
    public function __invoke(ContainerInterface $container): Filesystem
    {
        return new Filesystem();
    }
}
