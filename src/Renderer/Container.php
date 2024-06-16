<?php

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
