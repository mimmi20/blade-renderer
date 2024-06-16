<?php

declare(strict_types = 1);

namespace Mimmi20\Mezzio\BladeRenderer\Renderer;

use Jenssegers\Blade\Blade;
use Laminas\View\Model\ModelInterface;
use Laminas\View\Model\ModelInterface as Model;
use Laminas\View\Renderer\PhpRenderer;
use Traversable;

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
     * @param  string|ModelInterface                                $nameOrModel The script/resource process, or a view model
     * @param  array<string, mixed>|Traversable<string, mixed>|null $values      Values to use during rendering
     *
     * @return string The script output.
     *
     * @throws void
     *
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingNativeTypeHint
     */
    public function render($nameOrModel, $values = null): string
    {
        if ($nameOrModel instanceof Model) {
            $model       = $nameOrModel;
            $nameOrModel = $model->getTemplate();

            $values = $model->getVariables();
            unset($model);
        }

        if ($values instanceof Traversable) {
            $values = iterator_to_array($values);
        } elseif ($values instanceof \ArrayAccess) {

        }

        return $this->blade->make($nameOrModel, $values)->render();
    }
}
