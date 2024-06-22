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
use Mimmi20\Mezzio\BladeRenderer\Renderer\BladeRenderer;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;

use function assert;

final class BladeStrategyFactoryTest extends TestCase
{
    private BladeStrategyFactory $factory;

    /** @throws void */
    protected function setUp(): void
    {
        $this->factory = new BladeStrategyFactory();
    }

    /**
     * @throws Exception
     * @throws ContainerExceptionInterface
     */
    public function testInvocation(): void
    {
        $blade = new Blade('tests/views', 'tests/cache');

        $renderer = new BladeRenderer($blade);

        $container = $this->getMockBuilder(ContainerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $container->expects(self::once())
            ->method('get')
            ->with(BladeRenderer::class)
            ->willReturn($renderer);

        assert($container instanceof ContainerInterface);
        $strategy = ($this->factory)($container);

        self::assertInstanceOf(BladeStrategy::class, $strategy);

        self::assertSame($renderer, $strategy->getRenderer());
    }
}
