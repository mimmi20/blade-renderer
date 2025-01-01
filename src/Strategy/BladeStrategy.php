<?php

/**
 * This file is part of the mimmi20/blade-renderer package.
 *
 * Copyright (c) 2024-2025, Thomas Mueller <mimmi20@live.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Mimmi20\Mezzio\BladeRenderer\Strategy;

use Laminas\EventManager\EventManagerInterface;
use Laminas\EventManager\ListenerAggregateInterface;
use Laminas\EventManager\ListenerAggregateTrait;
use Laminas\View\Model;
use Laminas\View\ViewEvent;
use Mimmi20\Mezzio\BladeRenderer\Renderer\BladeRenderer;
use Override;

final class BladeStrategy implements ListenerAggregateInterface
{
    use ListenerAggregateTrait;

    /**
     * Placeholders that may hold content
     *
     * @var array<int|string, string>
     */
    private array $contentPlaceholders = [];

    /** @throws void */
    public function __construct(private readonly BladeRenderer $renderer)
    {
        // nothing to do
    }

    /**
     * Retrieve the composed renderer
     *
     * @throws void
     *
     * @api
     */
    public function getRenderer(): BladeRenderer
    {
        return $this->renderer;
    }

    /**
     * Set list of possible content placeholders
     *
     * @param  array<int|string, string> $contentPlaceholders
     *
     * @throws void
     *
     * @api
     */
    public function setContentPlaceholders(array $contentPlaceholders): self
    {
        $this->contentPlaceholders = $contentPlaceholders;

        return $this;
    }

    /**
     * Get list of possible content placeholders
     *
     * @return array<int|string, string>
     *
     * @throws void
     *
     * @api
     */
    public function getContentPlaceholders(): array
    {
        return $this->contentPlaceholders;
    }

    /**
     * Attach one or more listeners
     *
     * Implementors may add an optional $priority argument; the EventManager
     * implementation will pass this to the aggregate.
     *
     * @param int $priority
     *
     * @throws void
     *
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingNativeTypeHint
     */
    #[Override]
    public function attach(EventManagerInterface $events, $priority = 1): void
    {
        $this->listeners[] = $events->attach(
            ViewEvent::EVENT_RENDERER,
            [$this, 'selectRenderer'],
            $priority,
        );
        $this->listeners[] = $events->attach(
            ViewEvent::EVENT_RESPONSE,
            [$this, 'injectResponse'],
            $priority,
        );
    }

    /**
     * Select the BladeRenderer.
     *
     * @param ViewEvent<object|string|null> $e
     *
     * @throws void
     *
     * @api
     */
    public function selectRenderer(ViewEvent $e): BladeRenderer | null
    {
        $model = $e->getModel();

        if (!$model instanceof Model\ViewModel) {
            // no ViewModel present; do nothing
            return null;
        }

        if ($model instanceof Model\JsonModel) {
            // JsonModel present; do nothing
            return null;
        }

        if ($model instanceof Model\FeedModel) {
            // FeedModel present; do nothing
            return null;
        }

        return $this->renderer;
    }

    /**
     * Populate the response object from the View
     *
     * Populates the content of the response object from the view rendering
     * results.
     *
     * @param ViewEvent<object|string|null> $e
     *
     * @throws void
     *
     * @api
     */
    public function injectResponse(ViewEvent $e): void
    {
        $renderer = $e->getRenderer();
        $response = $e->getResponse();

        if ($renderer !== $this->renderer || $response === null) {
            return;
        }

        $result = $e->getResult();
        $response->setContent($result);
    }
}
