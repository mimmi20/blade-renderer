<?php

/**
 * This file is part of the mimmi20/blade-renderer package.
 *
 * Copyright (c) 2025, Thomas Mueller <mimmi20@live.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Mimmi20\Mezzio\BladeRenderer\Strategy;

use Mimmi20\Mezzio\BladeRenderer\Renderer\BladeRenderer;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

final class BladeStrategyFactory
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): BladeStrategy
    {
        return new BladeStrategy($container->get(BladeRenderer::class));
    }
}
