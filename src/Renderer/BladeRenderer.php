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

use Jenssegers\Blade\Blade;
use Laminas\View\Model\ModelInterface;
use Laminas\View\Renderer\PhpRenderer;
use Traversable;

use function iterator_to_array;

final class BladeRenderer extends PhpRenderer
{
    /** @throws void */
    public function __construct(private readonly Blade $blade)
    {
        parent::__construct();
    }

    /**
     * Processes a view script and returns the output.
     *
     * @param  ModelInterface|string                                $nameOrModel The script/resource process, or a view model
     * @param  array<string, mixed>|Traversable<string, mixed>|null $values      Values to use during rendering
     *
     * @return string the script output
     *
     * @throws void
     *
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingNativeTypeHint
     */
    public function render($nameOrModel, $values = null): string
    {
        if ($nameOrModel instanceof ModelInterface) {
            $model       = $nameOrModel;
            $nameOrModel = $model->getTemplate();

            $values = $model->getVariables();
            unset($model);
        }

        if ($values instanceof Traversable) {
            $values = iterator_to_array($values);
        } elseif ($values === null) {
            $values = [];
        }

        return $this->blade->make($nameOrModel, $values)->render();
    }
}
