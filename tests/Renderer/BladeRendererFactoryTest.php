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

namespace Mimmi20\Mezzio\BladeRenderer\Renderer;

use Jenssegers\Blade\Blade;
use Override;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use ReflectionException;
use ReflectionProperty;

use function assert;

final class BladeRendererFactoryTest extends TestCase
{
    private BladeRendererFactory $factory;

    /** @throws void */
    #[Override]
    protected function setUp(): void
    {
        $this->factory = new BladeRendererFactory();
    }

    /**
     * @throws Exception
     * @throws ContainerExceptionInterface
     * @throws ReflectionException
     */
    public function testInvocation(): void
    {
        $blade = new Blade('tests/views', 'tests/cache');

        $container = $this->getMockBuilder(ContainerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $container->expects(self::once())
            ->method('get')
            ->with(Blade::class)
            ->willReturn($blade);

        assert($container instanceof ContainerInterface);
        $renderer = ($this->factory)($container);

        self::assertInstanceOf(BladeRenderer::class, $renderer);

        $b = new ReflectionProperty($renderer, 'blade');

        self::assertSame($blade, $b->getValue($renderer));
    }
}
