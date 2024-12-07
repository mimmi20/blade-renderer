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

namespace Mimmi20\Mezzio\BladeRenderer\Strategy;

use Jenssegers\Blade\Blade;
use Laminas\EventManager\EventManagerInterface;
use Laminas\Stdlib\Response;
use Laminas\View\Exception\InvalidArgumentException;
use Laminas\View\Model\FeedModel;
use Laminas\View\Model\JsonModel;
use Laminas\View\Model\ViewModel;
use Laminas\View\ViewEvent;
use Mimmi20\Mezzio\BladeRenderer\Renderer\BladeRenderer;
use Override;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\TestCase;

final class BladeStrategyTest extends TestCase
{
    private BladeStrategy $bladeStrategy;
    private BladeRenderer $renderer;

    /** @throws void */
    #[Override]
    protected function setUp(): void
    {
        $blade = new Blade('tests/views', 'tests/cache');

        $this->renderer = new BladeRenderer($blade);

        $this->bladeStrategy = new BladeStrategy($this->renderer);
    }

    /** @throws Exception */
    public function testAttach(): void
    {
        $strategy = $this->bladeStrategy;

        $events  = $this->createMock(EventManagerInterface::class);
        $matcher = self::exactly(2);
        $events->expects($matcher)
            ->method('attach')
            ->willReturnCallback(
                static function (string $eventName, callable $listener, int $inputPriority = 1) use ($matcher, $strategy): callable {
                    $invocation = $matcher->numberOfInvocations();

                    match ($invocation) {
                        1 => self::assertSame(
                            ViewEvent::EVENT_RENDERER,
                            $eventName,
                            (string) $invocation,
                        ),
                        default => self::assertSame(
                            ViewEvent::EVENT_RESPONSE,
                            $eventName,
                            (string) $invocation,
                        ),
                    };

                    match ($invocation) {
                        1 => self::assertSame(
                            [$strategy, 'selectRenderer'],
                            $listener,
                            (string) $invocation,
                        ),
                        default => self::assertSame(
                            [$strategy, 'injectResponse'],
                            $listener,
                            (string) $invocation,
                        ),
                    };

                    match ($invocation) {
                        1 => self::assertSame(1, $inputPriority, (string) $invocation),
                        default => self::assertSame(1, $inputPriority, (string) $invocation),
                    };

                    return $listener;
                },
            );

        $strategy->attach($events);
    }

    /** @throws Exception */
    public function testAttachWithPriority(): void
    {
        $priority = 42;
        $strategy = $this->bladeStrategy;

        $events  = $this->createMock(EventManagerInterface::class);
        $matcher = self::exactly(2);
        $events->expects($matcher)
            ->method('attach')
            ->willReturnCallback(
                static function (string $eventName, callable $listener, int $inputPriority = 1) use ($matcher, $strategy, $priority): callable {
                    $invocation = $matcher->numberOfInvocations();

                    match ($invocation) {
                        1 => self::assertSame(
                            ViewEvent::EVENT_RENDERER,
                            $eventName,
                            (string) $invocation,
                        ),
                        default => self::assertSame(
                            ViewEvent::EVENT_RESPONSE,
                            $eventName,
                            (string) $invocation,
                        ),
                    };

                    match ($invocation) {
                        1 => self::assertSame(
                            [$strategy, 'selectRenderer'],
                            $listener,
                            (string) $invocation,
                        ),
                        default => self::assertSame(
                            [$strategy, 'injectResponse'],
                            $listener,
                            (string) $invocation,
                        ),
                    };

                    match ($invocation) {
                        1 => self::assertSame($priority, $inputPriority, (string) $invocation),
                        default => self::assertSame($priority, $inputPriority, (string) $invocation),
                    };

                    return $listener;
                },
            );

        $strategy->attach($events, $priority);
    }

    /** @throws Exception */
    public function testSetContentPlaceholders(): void
    {
        self::assertSame([], $this->bladeStrategy->getContentPlaceholders());

        $contentPlaceholders = ['abc', 'efg'];

        $this->bladeStrategy->setContentPlaceholders($contentPlaceholders);

        self::assertSame($contentPlaceholders, $this->bladeStrategy->getContentPlaceholders());
    }

    /** @throws Exception */
    public function testSelectRenderer(): void
    {
        $e = $this->createMock(ViewEvent::class);
        $e->expects(self::once())
            ->method('getModel')
            ->willReturn(null);

        self::assertNull($this->bladeStrategy->selectRenderer($e));
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testSelectRendererWithJsonModel(): void
    {
        $model = new JsonModel();

        $e = $this->createMock(ViewEvent::class);
        $e->expects(self::once())
            ->method('getModel')
            ->willReturn($model);

        self::assertNull($this->bladeStrategy->selectRenderer($e));
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testSelectRendererWithFeedModel(): void
    {
        $model = new FeedModel();

        $e = $this->createMock(ViewEvent::class);
        $e->expects(self::once())
            ->method('getModel')
            ->willReturn($model);

        self::assertNull($this->bladeStrategy->selectRenderer($e));
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testSelectRendererWithViewModel(): void
    {
        $model = new ViewModel();

        $e = $this->createMock(ViewEvent::class);
        $e->expects(self::once())
            ->method('getModel')
            ->willReturn($model);

        self::assertSame($this->renderer, $this->bladeStrategy->selectRenderer($e));
    }

    /** @throws Exception */
    public function testInjectResponse(): void
    {
        $e = $this->createMock(ViewEvent::class);
        $e->expects(self::once())
            ->method('getRenderer')
            ->willReturn(null);
        $e->expects(self::once())
            ->method('getResponse')
            ->willReturn(null);
        $e->expects(self::never())
            ->method('getResult');

        $this->bladeStrategy->injectResponse($e);
    }

    /** @throws Exception */
    public function testInjectResponse2(): void
    {
        $e = $this->createMock(ViewEvent::class);
        $e->expects(self::once())
            ->method('getRenderer')
            ->willReturn($this->renderer);
        $e->expects(self::once())
            ->method('getResponse')
            ->willReturn(null);
        $e->expects(self::never())
            ->method('getResult');

        $this->bladeStrategy->injectResponse($e);
    }

    /** @throws Exception */
    public function testInjectResponse3(): void
    {
        $response = $this->createMock(Response::class);
        $response->expects(self::never())
            ->method('setContent');

        $e = $this->createMock(ViewEvent::class);
        $e->expects(self::once())
            ->method('getRenderer')
            ->willReturn(null);
        $e->expects(self::once())
            ->method('getResponse')
            ->willReturn($response);
        $e->expects(self::never())
            ->method('getResult');

        $this->bladeStrategy->injectResponse($e);
    }

    /** @throws Exception */
    public function testInjectResponse4(): void
    {
        $content = 'test';

        $response = $this->createMock(Response::class);
        $response->expects(self::once())
            ->method('setContent')
            ->with($content);

        $e = $this->createMock(ViewEvent::class);
        $e->expects(self::once())
            ->method('getRenderer')
            ->willReturn($this->renderer);
        $e->expects(self::once())
            ->method('getResponse')
            ->willReturn($response);
        $e->expects(self::once())
            ->method('getResult')
            ->willReturn($content);

        $this->bladeStrategy->injectResponse($e);
    }
}
