<?php

declare(strict_types=1);

/*
 * This file is part of the Sonata Project package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonata\Cache\Tests\Adapter\Cache;

use PHPUnit\Framework\TestCase;
use Sonata\Cache\Adapter\Counter\ApcCounter;
use Sonata\Cache\Counter;

class ApcCounterTest extends TestCase
{
    protected function setUp(): void
    {
        if (!\function_exists('apc_store')) {
            static::markTestSkipped('APC is not installed');
        }

        if (0 === ini_get('apc.enable_cli')) {
            static::markTestSkipped('APC is not enabled in cli, please add apc.enable_cli=On into the apc.ini file');
        }

        apc_clear_cache('user');
    }

    public function testCounterBackend(): void
    {
        $backend = new ApcCounter('prefix');

        $counter = $backend->set(Counter::create('mycounter', 10));

        static::assertInstanceOf('Sonata\Cache\Counter', $counter);
        static::assertSame(10, $counter->getValue());
        static::assertSame('mycounter', $counter->getName());

        $counter = $backend->get('mycounter');
        static::assertInstanceOf('Sonata\Cache\Counter', $counter);
        static::assertSame(10, $counter->getValue());
        static::assertSame('mycounter', $counter->getName());

        $counter = $backend->increment($counter);
        static::assertSame(11, $counter->getValue());

        $counter = $backend->increment($counter, 10);
        static::assertSame(21, $counter->getValue());

        $counter = $backend->decrement($counter);
        static::assertSame(20, $counter->getValue());

        $counter = $backend->decrement($counter, 30);
        static::assertSame(-10, $counter->getValue());
    }

    public function testNonExistantKey(): void
    {
        $backend = new ApcCounter('prefix');

        $counter = $backend->increment(Counter::create('mycounter', 10));

        static::assertSame(11, $counter->getValue());
    }
}
