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

namespace Mimmi20\Mezzio\BladeRenderer\Renderer;

use Closure;
use Illuminate\Container\Container as BaseContainer;

final class Container extends BaseContainer
{
    /** @var array<int, Closure> */
    protected array $terminatingCallbacks = [];

    /** @throws void */
    public function terminating(Closure $callback): self
    {
        $this->terminatingCallbacks[] = $callback;

        return $this;
    }

    /** @throws void */
    public function terminate(): void
    {
        foreach ($this->terminatingCallbacks as $terminatingCallback) {
            $terminatingCallback();
        }
    }
}
